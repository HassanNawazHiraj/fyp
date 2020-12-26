<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($validatedData)) {
            return response(['message' => 'Invalid credentials', 'error' => 'invalid_grant'], 401);
        }

        $accessToken = auth()->user()->createToken('auth_token')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
