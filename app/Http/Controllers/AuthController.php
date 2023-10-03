<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if(!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials!'
            ], 401);
        }

        return $this->process($user);
    }

    public function register (RegisterRequest $request) {
        $data = $request->validated();

        $newUser = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->process($newUser);
    }

    public function process(User $user) {
        $token = $user->createToken('auth_token');
        $plainTextToken = $token->plainTextToken;
    
        return response()->json([
            'user' => new UserResource($user),
            'token' => $plainTextToken,
        ]);
    }

    public function logout (Request $request) {
        $request->user()->currentAccessToken()->delete();
        $cookie = cookie()->forget('token');
        
        return response()->json([
            'message' => 'Logged out successfully'
        ])->withCookie($cookie);
        
    }
}
