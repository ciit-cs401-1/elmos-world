<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function authenticate(Request $request)
    {
        $validateData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validateData, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => 'Credentials does not match!'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', "Logged out!");
    }

    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        try {
            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password']),
            ]);

            $user->roles()->attach(3);
        } catch (Exception $e) {
            throw $e;
        };

        Auth::login($user);

        return redirect('/');
    }

    public function show()
    {
        $user = User::findOrFail(auth()->id());
        return view('dashboard.account', ['user' => $user]);
    }
}
