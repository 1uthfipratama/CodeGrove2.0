<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view() {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(
            ['username' => $credentials['username'], 'password' => $credentials['password']],
            $request->boolean('remember')
        )) {
            if (Auth::user()->role == "admin") {
                return redirect('/admin');
            }

            return redirect('/');
        }

        return redirect()->back()->withInput($request->only('username'))->withErrors([
            'username' => 'Invalid username or password.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    
}
