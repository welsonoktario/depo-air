<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Throwable;

class KurirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depo = Auth::user()->depo;
        $kurirs = Kurir::query()
            ->with('user')
            ->where('depo_id', $depo->id)
            ->get();

        return View::make('kurir.index', compact('kurirs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('kurir.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $depo = Auth::user()->depo;
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'password' => bcrypt($request->password),
                'role' => 'Kurir'
            ]);
            $user->kurir()->create([
                'depo_id' => $depo->id,
                'alamat' => $request->alamat
            ]);

            DB::commit();
        } catch (Throwable $err) {
            DB::rollBack();

            return Redirect::back()->with('error', $err->getMessage());
        }

        return Redirect::route('kurir.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kurir  $kurir
     * @return \Illuminate\Http\Response
     */
    public function show(Kurir $kurir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kurir  $kurir
     * @return \Illuminate\Http\Response
     */
    public function edit(Kurir $kurir)
    {
        $kurir->load('user');

        return View::make('kurir.edit', compact('kurir'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kurir  $kurir
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kurir $kurir)
    {
        DB::beginTransaction();

        try {
            $kurir->update([
                'alamat' => $request->alamat
            ]);

            $kurir->user()->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon
            ]);

            DB::commit();
        } catch (Throwable $err) {
            DB::rollBack();

            return Redirect::back()->with('error', $err->getMessage());
        }

        return Redirect::route('kurir.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kurir  $kurir
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kurir $kurir)
    {
        $kurir->load('user');
        $kurir->user->delete();

        return Redirect::route('kurir.index');
    }
}
