<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Depo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\DB;
use Throwable;

class DepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'Super Admin') {
            $depos = Depo::all();

            return View::make('depo.index', compact('depos'));
        } elseif ($user->role == 'Admin') {
            $depo = Depo::firstWhere('user_id', $user->id);

            return View::make('depo.index', compact('depo'));
        } else {
            return Redirect::route('home.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('depo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $depo = new Depo();
            $depo->user_id = $request->depo_user;
            $depo->nama = $request->depo_nama;
            $depo->alamat = $request->depo_alamat;
            $depo->lokasi = new Point($request->depo_lokasi_long, $request->depo_lokasi_lat);
            $depo->is_utama = $request->depo_is_utama;

            if ($request->buat_admin) {
                $user = User::query()->create([
                    'nama' => $request->user_nama,
                    'email' => $request->user_email,
                    'password' => bcrypt($request->user_password)
                ]);

                $depo->user_id = $user->id;
            }

            DB::commit();

            return Redirect::route('depo.index')->with([
                'status' => 'OK',
                'msg' => 'Depo berhasil ditambahkan'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return Redirect::route('depo.index')->with([
                'status' => 'Error',
                'msg' => $e->getMessage
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Depo  $depo
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Depo $depo)
    {
        return View::make('depo.show', compact('depo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Depo  $depo
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Depo $depo)
    {
        $depo->load('user');

        return View::make('depo.edit', compact('depo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Depo  $depo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Depo $depo)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['lokasi'] = new Point($request->lokasi->lat, $request->lokasi->long);
            $depo->update($data);

            DB::commit();

            return Redirect::route('depo.index')->with([
                'status' => 'OK',
                'msg' => 'Depo berhasil diperbarui'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return Redirect::route('depo.index')->with([
                'status' => 'Error',
                'msg' => $e->getMessage
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Depo  $depo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Depo $depo)
    {
        DB::beginTransaction();

        try {
            $depo->user()->delete();

            DB::commit();

            return Redirect::route('depo.index')->with([
                'status' => 'OK',
                'msg' => 'Depo berhasil dihapus'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return Redirect::route('depo.index')->with([
                'status' => 'Error',
                'msg' => $e->getMessage
            ]);
        }
    }
}
