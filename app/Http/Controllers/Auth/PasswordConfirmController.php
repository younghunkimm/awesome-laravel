<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordConfirmRequest;
use Illuminate\View\View;

class PasswordConfirmController extends Controller
{
    public function showPasswordConfirmationForm(): View
    {
        return view('auth.confirm-password');
    }

    public function confirm(PasswordConfirmRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
