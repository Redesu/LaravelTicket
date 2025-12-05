<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Auth;
use Illuminate\Validation\ValidationException;
use Log;

class RegisterController extends Controller
{
    //
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        Log::info('Registration attempt for email: ' . $validated['email']);

        $existingUser = User::where('email', $validated['email'])->first();

        if ($existingUser) {
            if ($existingUser->created_at > now()->subSeconds(20)) {
                Auth::login($existingUser, remember: true);
                return redirect('/')->with('success', 'Usuário registrado com sucesso!');
            }

            throw ValidationException::withMessages([
                'email' => ['O email já está em uso.'],
            ]);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Auth::login($user, remember: true);

        return redirect('/')->with('success', 'Usuário registrado com sucesso!');
    }
}
