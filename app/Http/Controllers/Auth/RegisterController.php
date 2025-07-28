<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed' 
        ]);

        $user = User::create([
            'nome'=> $validated['nome'],
            'email'=> $validated['email'],
            'password'=> bcrypt($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('/')->with('success','');
    }
}
