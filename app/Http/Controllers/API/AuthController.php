<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $attempt = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$attempt) {
            return Response::json([
                'status' => 'GAGAL',
                'msg' => 'Email atau password salah'
            ]);
        }

        $user = User::query()
            ->with(['customer.barangs.kategori'])
            ->firstWhere('email', $request->email);

        if (!$user) {
            return Response::json([
                'status' => 'GAGAL',
                'msg' => 'Email atau password salah'
            ]);
        }

        if ($user->role == 'Admin' || $user->role == 'Super Admin') {
            return Response::json([
                'status' => 'GAGAL',
                'msg' => 'Email atau password salah'
            ]);
        }

        return Response::json([
            'status' => 'OK',
            'data' => [
                'user' => new UserResource($user),
                'token' => $user->createToken($user->email)->plainTextToken
            ]
        ]);
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::query()
            ->create([
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
            ]);

            $user->customer()->create([
                'alamat' => $request->alamat,
                'lokasi' => new Point($request->lokasi->lat, $request->lokasi->long)
            ]);

            DB::commit();

            $user->load('customer');
        } catch (Throwable $err) {
            return Response::json([
                'status' => 'GAGAL',
                'msg' => $err->getMessage()
            ], 500);
        }

        return Response::json([
            'status' => 'OK',
            'data' => [
                'user' => new UserResource($user),
                'token' => $user->createToken($user->email)->plainTextToken
            ]
        ]);
    }
}
