<?php

namespace App\Http\Controllers\API;

use App\Events\TransaksiCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\BarangCollection;
use App\Http\Resources\DepoCollection;
use App\Http\Resources\TransaksiCollection;
use App\Http\Resources\TransaksiResource;
use App\Models\Barang;
use App\Models\Depo;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Throwable;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksis = Transaksi::query()
            ->with(['depo', 'kurir.user', 'barangs'])
            ->whereHas('customer', fn ($q) => $q->where('user_id', Auth::id()))
            ->orderBy('tanggal', 'desc')
            ->get();

        return new TransaksiCollection($transaksis);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $barangs = Barang::all();

        return new BarangCollection($barangs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $customer = Auth::user()->customer;
            $barangs = [];
            foreach ($request->cart as $cart) {
                $barangs[$cart['barang']['id']] = (int) $cart['jumlah'];
            }
            $depo = $this->findDepo($barangs, $customer->lokasi);

            if ($depo) {
                DB::beginTransaction();

                $transaksi = $customer
                    ->transaksis()
                    ->create([
                        'depo_id' => $depo->id,
                        'customer_id' => $customer->id,
                        'tanggal' => now('Asia/Jakarta'),
                        'status' => 'Menunggu Pembayaran'
                    ]);

                $transaksiDetails = [];

                foreach ($barangs as $barang => $jumlah) {
                    $transaksiDetails[$barang] = ['jumlah' => $jumlah];
                }

                $transaksi->barangs()->sync($transaksiDetails);
                $customer->barangs()->sync([]);

                DB::commit();

                $transaksi = $transaksi->load(['depo', 'barangs', 'customer.user']);
                TransaksiCreated::broadcast($transaksi);

                return TransaksiResource::make($transaksi);
            } else {
                return Response::json([
                    'status' => 'GAGAL',
                    'msg' => 'Barang tidak tersedia'
                ], 500);
            }
        } catch (Throwable $err) {
            Log::error($err->getMessage());
            abort(500, $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['barangs', 'depo', 'kurir.user']);

        return new TransaksiResource($transaksi);
    }

    // cari 1 depo terdekat yang stok pesanan mencukupi
    private function findDepo($barangs, $lokasi)
    {
        $ids = collect($barangs)->map(fn ($barang, $key) => $key)->values();
        $listDepo = collect([]);
        $depos = Depo::query()
            ->with('barangs')
            ->whereHas('barangs', fn ($q) => $q->whereIn('barang_id', $ids))
            ->get();

        foreach ($depos as $depo) {
            foreach ($depo->barangs as $barang) {
                foreach ($barangs as $id => $stok) {
                    if ($barang->id == $id && $barang->pivot->stok >= $stok) {
                        $listDepo->add($depo);
                    }
                }
            }
        }
        $listDepo = $listDepo->groupBy('id');
        $listDepo = $listDepo->filter(function ($depo) use ($ids) {
            return $depo->count() == $ids->count();
        });
        $listDepo = $listDepo->map(fn ($depo) => $depo->unique('id'))->values();

        foreach ($listDepo as $depo) {
            $lokasiDepo = $depo[0]->lokasi;
            $distance = $this->codexworldGetDistanceOpt(
                $lokasi->latitude,
                $lokasi->longitude,
                $lokasiDepo->latitude,
                $lokasiDepo->longitude
            );
            $depo[0]['distance'] = $distance;
        }

        $nearest = $listDepo->sortByDesc(function ($depo) {
            return $depo[0]->distance;
        });

        return $nearest->first()[0];
    }

    /**
     * Optimized algorithm from http://www.codexworld.com
     *
     * @param float $latitudeFrom
     * @param float $longitudeFrom
     * @param float $latitudeTo
     * @param float $longitudeTo
     *
     * @return float [km]
     */
    public function codexworldGetDistanceOpt($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $rad = M_PI / 180;
        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad)
        * sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad)
        * cos($latitudeTo * $rad) * cos($theta * $rad);

        return acos($dist) / $rad * 60 *  1.853;
    }
}
