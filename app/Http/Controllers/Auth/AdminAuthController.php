<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$admin) {
            return response()->json([
                'message' => 'Email atau username tidak ditemukan.',
            ], 404);
        }

        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Password yang Anda masukkan salah.',
            ], 401);
        }

        $token = $admin->createToken('admin_token')->plainTextToken;

        return response()->json([
            'message' => 'Berhasil login.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'admin' => $admin,
        ]);
    }

    public function logout(Request $request)
    {
        if (!($request->user() instanceof \App\Models\Admin)) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout.']);
    }
}
