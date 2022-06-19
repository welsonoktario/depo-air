<?php

namespace App\Http\Controllers\Web;

use App\Events\TransaksiUpdated;
use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

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
        tap($transaksi)->update([
            'status' => $request->aksi,
        ]);

        broadcast(new TransaksiUpdated($transaksi))->toOthers();

        return Response::json(['status' => 'OK']);
    }
}
