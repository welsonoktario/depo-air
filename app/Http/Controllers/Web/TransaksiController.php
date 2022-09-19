<?php

namespace App\Http\Controllers\Web;

use App\Events\TransaksiUpdated;
use App\Http\Controllers\Controller;
use App\Models\Depo;
use App\Models\Kurir;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('status');

        if ($transaksis->has('Menunggu Konfirmasi')) {
            $transaksis['Menunggu Konfirmasi'] = collect($transaksis['Menunggu Konfirmasi'])->map(function ($transaksi) {
                $tmp = $transaksi;
                $tmp['selected'] = false;

                return $tmp;
            });
        }

        $kurirs = Kurir::query()
            ->with('user')
            ->where('depo_id', Auth::user()->depo->id)
            ->where('status', 'Idle')
            ->get();

        return View::make('transaksi.index', compact('transaksis', 'kurirs'));
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
        $kurirs = Kurir::query()
            ->with('user')
            ->where('depo_id', Auth::user()->depo->id)
            ->where('status', 'Idle')
            ->get();

        return View::make('transaksi.edit', compact('transaksi', 'kurirs'));
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

            if ($request->aksi == 'Batal') {
                tap($transaksi)->update([
                    'status' => $request->aksi,
                    'alasan_pembatalan' => $request->alasan,
                ]);
            } else {
                tap($transaksi)->update([
                    'status' => $request->aksi,
                ]);
            }

            if ($request->aksi == 'Diproses') {
                $transaksi->update(['kurir_id' => $request->kurir]);
                $transaksi->kurir()->update([
                    'status' => 'Mengirim'
                ]);

                foreach ($transaksi->barangs as $barang) {
                    $barangDepo = collect($depo->barangs)->firstWhere('id', $barang->id);

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

    public function checkout(Request $request)
    {
        $depo = Depo::with('barangs')
            ->where('user_id', Auth::id())
            ->first();
        $transaksis = Transaksi::query()
            ->whereIn('id', $request->transaksis)
            ->get();
        $kurir = Kurir::query()->find($request->kurir);

        DB::beginTransaction();

        try {
            foreach ($transaksis as $transaksi) {
                $transaksi->update([
                    'kurir_id' => $kurir->id,
                    'status' => 'Diproses'
                ]);

                foreach ($transaksi->barangs as $barang) {
                    $barangDepo = collect($depo->barangs)->firstWhere('id', $barang->id);

                    $depo->barangs()->sync([
                        $barang->id => ['stok' => $barangDepo->pivot->stok - $barang->pivot->jumlah]
                    ], false);
                }
            }

            $kurir->update([
                'status' => 'Mengirim'
            ]);

            DB::commit();

            return Response::json(['status' => 'ok'], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return Response::json(['status' => 'err', 'msg' => $e->getMessage()], 500);
        }
    }
}
