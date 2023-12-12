<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class ResgisterController extends Controller
{
    /**
     * 회원가입 폼
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 회원가입
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            ...$request->validated(),
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user);

        event(new Registered($user));

        return to_route('verification.notice');
    }
}
