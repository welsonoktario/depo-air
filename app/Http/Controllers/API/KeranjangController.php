<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\KeranjangCollection;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Throwable;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        $barangs = $customer->barangs;

        return new KeranjangCollection($barangs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = Auth::user()->customer;

        try {
            DB::beginTransaction();

            $customer->barangs()->sync([
                $request->barang => ['jumlah' => $request->jumlah],
            ], false);

            DB::commit();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return abort(500, $e->getMessage());
        }

        return Response::json(['msg' => 'OK']);
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
        //
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
        $customer = Auth::user()->customer;
        Log::debug($barang);

        try {
            DB::beginTransaction();

            $customer->barangs()->sync([
                $barang->id => ['jumlah' => $request->jumlah]
            ], false);

            DB::commit();
        } catch (Throwable $e) {
            return abort(500, $e->getMessage());
        }

        return Response::json(['msg' => 'OK']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        $customer = Auth::user()->customer;
        $customer->barangs()->detach([$barang->id]);

        return Response::json(['msg' => 'OK']);
    }
}
