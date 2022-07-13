<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
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
        DB::beginTransaction();

        try {
            $user = User::query()
                ->create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'telepon' => $request->telepon,
                    'password' => bcrypt($request->password),
                    'role' => 'Customer'
                ]);

            $user->customer()->create([]);

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

    public function update(Request $request, User $user)
    {
        $update = $user->update([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'email' => $request->email
        ]);

        if (!$update) {
            abort(500, 'Terjadi kesalahan mengubah profil');
        }

        return Response::json([
            'status' => 'OK',
            'msg' => 'Profil berhasil diperbarui'
        ]);
    }

    public function password(Request $request, User $user)
    {
        $update = $user->update([ 'password' => bcrypt($request->baru) ]);

        if (!$update) {
            abort(500, 'Terjadi kesalahan mengubah password');
        }

        return Response::json([
            'status' => 'OK',
            'msg' => 'Password berhasil diperbarui'
        ]);
    }
}
