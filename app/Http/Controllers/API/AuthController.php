<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Response;

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
            ->with('customer')
            ->firstWhere('email', $request->email);

        if (!$user) {
            return Response::json([
                'status' => 'GAGAL',
                'msg' => 'Email atau password salah'
            ]);
        }
        Log::debug($user);

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
}