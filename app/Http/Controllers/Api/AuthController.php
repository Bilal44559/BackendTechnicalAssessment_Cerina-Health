<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // return $request;
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!Auth::attempt($request->only('email','password'))) {
            return response()->json(['message'=>'Invalid credentials'], 401);
        }

        $token = $request->user()->createToken('api')->plainTextToken;

        return response()->json([
            'token'=>$token,
            'user' => $user
            ]);
    }
}
