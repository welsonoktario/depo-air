<?php

namespace App\Http\Controllers\Web;

use App\Events\TransaksiUpdated;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Depo;
use App\Models\Transaksi;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
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
            ->with(['customer.user', 'barangs'])
            ->whereDepoId(Auth::user()->depo->id)
            ->get();

        return View::make('transaksi.index', compact('transaksis'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['customer', 'kurir', 'barangs']);

        return View::make('transaksi.edit', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        $transaksi->load(['customer', 'kurir', 'barangs']);

        return View::make('transaksi.edit', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $transaksi->load(['barangs']);
        $depo = Depo::with('barangs')
            ->where('user_id', Auth::id())
            ->first();

        try {
            DB::beginTransaction();

            tap($transaksi)->update([
                'status' => $request->aksi,
            ]);

            if ($request->aksi == 'Dikirim') {
                foreach ($transaksi->barangs as $barang) {
                    $barangDepo = collect($depo->barangs)->firstWhere('id', $barang->id);
                    Log::debug($barangDepo);
                    $depo->barangs()->sync([
                        $barang->id => ['stok' => $barangDepo->pivot->stok - $barang->pivot->jumlah]
                    ], false);
                }
            }

            DB::commit();
        } catch (Throwable $err) {
            DB::rollBack();

            return Response::json(['status' => 'GAGAL', 'msg' => $err->getMessage()]);
        }

        broadcast(new TransaksiUpdated($transaksi))->toOthers();

        return Response::json(['status' => 'OK']);
    }
}
