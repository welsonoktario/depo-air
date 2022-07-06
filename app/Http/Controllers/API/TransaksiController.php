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
            $distance = $this->haversineGreatCircleDistance(
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
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
