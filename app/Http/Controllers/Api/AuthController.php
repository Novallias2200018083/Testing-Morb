<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse; 
use App\Models\User;

class AuthController extends Controller
{
    
    public function login(Request $request): JsonResponse
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

       
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Kombinasi email atau password salah.'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

       
        if (!$user->is_active) {
            
            Auth::guard('web')->logout(); 
            
            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan oleh administrator.'
            ], 403);
        }

    
        
        $token = $user->createToken('staff_api_token')->plainTextToken;

       
        return response()->json([
            'message' => 'Login Berhasil',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->load('activeCounter'),
            ]
        ], 200);
    }

    
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil. Sesi diakhiri.'
        ], 200);
    }

    
    public function me(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->load('activeCounter')
        );
    }
}