<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Depo;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Log;

class InventoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depo = Auth::user()->depo;
        $barangsAll = Barang::all();
        $barangs = $depo->load(['barangs'])->barangs;

        return View::make('inventori.index', compact('barangs', 'barangsAll'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategoris = Kategori::all();

        return View::make('inventori.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        $barangDepo = Depo::query()
            ->with(['barangs' => fn ($q) => $q->with('kategori')->where('id', $barang->id)])
            ->firstWhere('user_id', Auth::id())
            ->barangs;

        if ($barangDepo->count()) {
            $barang = $barangDepo[0];
        }

        return View::make('inventori.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        $depo = Auth::user()->depo;

        $depo->barangs()->sync([$barang->id => ['stok' => $request->stok]], false);

        return Redirect::route('inventori.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        //
    }
}
