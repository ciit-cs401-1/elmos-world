<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    public function showEmailInput()
    {
        return view('auth.forgot-email');
    }

    public function showForgot(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.forgot', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function authenticate(Request $request)
    {
        $validateData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validateData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login date
            $user = Auth::user();
            $user->last_login_date = now();
            $user->save();

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => 'Credentials do not match!'
        ]);
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64); // raw token

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => bcrypt($token), 'created_at' => Carbon::now()]
        );

        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // Map all possible error statuses to ValidationException messages
        $messages = [
            Password::INVALID_USER   => 'Email not found.',
            Password::INVALID_TOKEN  => 'Unauthorized change! The token is invalid or expired.',
            Password::RESET_THROTTLED => 'Too many attempts. Please try again later.',
            Password::RESET_LINK_SENT => 'Reset link sent successfully.', // Rare here but can appear
        ];

        throw ValidationException::withMessages([
            'email' => $messages[$status] ?? 'Password reset failed.'
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
                'registration_date' => now(),
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

        if ($user->roles->first()->role_name == 'S') {
            return view('profile.show', ['user' => $user]);
        } else {
            return view('dashboard.account', ['user' => $user]);
        }
    }
}
