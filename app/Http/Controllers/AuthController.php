<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken,
        ]);
    }

    public function signup(SignupRequest $request)
    {
        $data = $request->validated();

        $name = $data['name'];
        $slug = Str::slug($name);
        $userWithSlug = User::where('slug', $slug)->first();

        if ($userWithSlug) {
            $slug = $slug . '-' . time();
        }

        $user = User::create([
            'name' => $name,
            'email' => $data['email'],
            'slug' => $slug,
            'password' => Hash::make($data['password']),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken,
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out'
        ]);
    }
}
