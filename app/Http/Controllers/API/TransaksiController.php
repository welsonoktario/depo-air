<?php

namespace App\Http\Controllers\API;

use App\Events\TransaksiCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\BarangCollection;
use App\Http\Resources\TransaksiCollection;
use App\Http\Resources\TransaksiResource;
use App\Models\Barang;
use App\Models\Depo;
use App\Models\Transaksi;
use App\Models\User;
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
            ->with(['depo', 'kurir', 'barangs'])
            ->whereHas('customer', fn ($q) => $q->whereUserId(Auth::id()))
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
            $customer = User::query()->find($request->user)->customer;
            $barangs = [];
            foreach ($request->cart as $cart) {
                $barangs[$cart['barang']['id']] = $cart['jumlah'];
            }
            $depo = $this->findDepo($barangs, $customer->lokasi);
            Log::debug($depo);

            if ($depo) {
                DB::beginTransaction();

                $transaksi = $customer
                    ->transaksis()
                    ->create([
                        'depo_id' => $depo->id,
                        'customer_id' => $customer->id,
                    ]);

                $transaksiDetails = [];

                foreach ($barangs as $barang => $jumlah) {
                    $transaksiDetails[$barang] = ['jumlah' => $jumlah];
                }

                $transaksi->barangs()->sync($transaksiDetails);

                DB::commit();

                $transaksi = $transaksi->load(['depo', 'barangs', 'customer.user']);
                TransaksiCreated::broadcast($transaksi);

                return TransaksiResource::make($transaksi);
            } else {
                return Response::json(['status' => 'GAGAL', 'msg' => 'Barang tidak tersedia'], 500);
            }
        } catch (Throwable $err) {
            Log::error($err->getMessage());
            abort('500', $err->getMessage());
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
        $depo = Depo::query()
            ->select('depos.*')
            ->join('depo_barangs', 'depo_barangs.depo_id', '=', 'depos.id');

        // cari depo yang stoknya cukup
        foreach ($barangs as $id => $stok) {
            $depo = $depo->where([
                ['depo_barangs.barang_id', '=', $id],
                ['depo_barangs.stok', '>=', $stok]
            ]);
        }

        // urutkan berdasarkan jarak terdekat
        $depo = $depo->orderByDistance('lokasi', $lokasi)
            ->first();

        return $depo;
    }
}
