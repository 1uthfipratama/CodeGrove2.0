<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function view() {
        return view('register');
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'alpha_dash', 'unique:users', 'regex:/^[a-zA-Z0-9_.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]+$/'],
            'dob' => ['required', 'date'],
        ], [
            'username.required' => 'Username is required.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes, and underscores.',
            'username.regex' => 'Username may only include letters, numbers, underscores, or dots.',
            'username.unique' => 'Username has been used before.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email has been used before.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include a capital letter, a symbol, and a number.',
            'password.confirmed' => 'Password confirmation does not match.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Date of birth must be a valid date.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'display_picture_path' => 'default.svg',
            'role' => 'user'
        ]);
        
        $userId = $user->id;
        Auth::login($user);
    
        return redirect()->route('select-language-view', ['userId' => $userId])
            ->with('success', 'Account created successfully! Choose your favorite languages to personalize your feed.');

    }
}
