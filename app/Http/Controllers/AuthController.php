<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            // 'user_uid'      => 'required',
            'name'          => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            // 'user_uid'      => $request->user_uid,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Register berhasil',
            'token'         => $token,
            'user'          => $user
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'password'       => 'required',
        ]);

        $user = User::where('name', $request->name)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->join([
                'message' => 'Username atau password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'message'       => 'Login berhasil',
            'token'         => $token,
            'user'          => $user
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    // USER PROFILE
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // FORGOT PASSWORD
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Link reset password dikirim ke email'])
            : response()->json(['message' => 'Email tidak ditemukan']);
    }
}
