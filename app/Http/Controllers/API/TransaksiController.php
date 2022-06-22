<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarangCollection;
use App\Http\Resources\DepoResource;
use App\Http\Resources\TransaksiCollection;
use App\Http\Resources\TransaksiResource;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Depo;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $customer = Auth::user()->customer;
            $depo = $this->findDepo([1 => 2], $customer->lokasi);

            DB::beginTransaction();
            
            $transaksi = $customer
                ->transaksis()
                ->create($request->all());

            $transaksi->associate($depo);
            
            $transaksi->barangs()
                ->sync($request->barangs);

            DB::commit();

            return new TransaksiResource($transaksi);
        } catch (Throwable $err) {
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

    private function findDepo(array $barangs, $lokasi)
    {
        $depo = Depo::query()
            ->select('depos.*')
            ->join('depo_barangs', 'depo_barangs.depo_id', '=', 'depos.id');

        foreach ($barangs as $id => $stok) {
            $depo = $depo->where([
                ['depo_barangs.barang_id', '=', $id],
                ['depo_barangs.stok', '>=', $stok]
            ]);
        }

        $depo = $depo->orderByDistance('lokasi', $lokasi)
            ->first();

        return $depo;
    }
}
