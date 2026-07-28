<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $cre = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (Auth::attempt($cre, true)) {
            return redirect()->route('admin.dashboard')->withSuccess('Login Success');
        }

        return back()->withErrors(['email' => 'Wrong Information!'])->withInput();
    }
}
