<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // Fungsi Login Admin
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        // Coba validasi email dan password, jika benar langsung generate token JWT
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau Password Admin salah!'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    // Fungsi Ambil Data Profil Admin yang sedang login via token
    public function me(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::guard('api')->user()
        ]);
    }

    // Fungsi Logout
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar sistem (Logout)'
        ]);
    }

    // Helper format respon token JWT
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60 // waktu kedaluwarsa token (menit)
        ]);
    }
}
