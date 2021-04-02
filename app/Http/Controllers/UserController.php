<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        return response([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $token = $user->createToken(env('SANCTUM_SECRET_KEY'))->plainTextToken;

        $userArr = Arr::add($user, 'token', $token);

        return response([
            'success' => true,
            'user' => $userArr,
        ]);

    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
    }
}