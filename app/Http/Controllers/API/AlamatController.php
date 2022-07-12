<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlamatCollection;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Response;
use Throwable;

class AlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        $alamats = Alamat::query()
            ->where('customer_id', $customer->id)
            ->get();

        return new AlamatCollection($alamats);
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

        DB::beginTransaction();

        try {
            $customer->alamats()->create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'lokasi' => new Point($request->lokasi->lat, $request->lokasi->lng),
                'isUtama' => $request->isUtama,
            ]);

            DB::commit();
        } catch (Throwable $err) {
            abort(500, $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alamat  $alamat
     * @return \Illuminate\Http\Response
     */
    public function show(Alamat $alamat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alamat  $alamat
     * @return \Illuminate\Http\Response
     */
    public function edit(Alamat $alamat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alamat  $alamat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alamat $alamat)
    {
        $alamat->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'lokasi' => new Point($request->lokasi->lat, $request->lokasi->lng),
            'isUtama' => $request->isUtama,
        ]);

        return Response::json([
            'status' => 'OK',
            'msg' => 'Alamat berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alamat  $alamat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alamat $alamat)
    {
        $alamat->delete();

        return Response::json([
            'status' => 'OK',
            'msg' => 'Alamat berhasil dihapus'
        ]);
    }
}
