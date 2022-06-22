<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepoCollection;
use App\Http\Resources\DepoResource;
use App\Models\Depo;
use Illuminate\Http\Request;

class DepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depos = Depo::all();

        return new DepoCollection($depos);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Depo  $depo
     * @return \Illuminate\Http\Response
     */
    public function show(Depo $depo)
    {
        $depo->load('barangs');
        
        return new DepoResource($depo);
    }
}
