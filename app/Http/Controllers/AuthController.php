<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Identifiants invalides.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'cin' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $alreadyLinkedClient = Client::where('email', $validated['email'])
            ->orWhere('cin', $validated['cin'])
            ->whereHas('user')
            ->exists();

        if ($alreadyLinkedClient) {
            return back()
                ->withErrors(['email' => 'Ce client possede deja un compte. Connectez-vous.'])
                ->withInput();
        }

        $user = DB::transaction(function () use ($validated) {
            $client = Client::where('email', $validated['email'])
                ->orWhere('cin', $validated['cin'])
                ->first();

            if ($client) {
                $client->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'birth_date' => $validated['birth_date'],
                    'cin' => $validated['cin'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'address' => $validated['address'],
                ]);
            } else {
                $client = Client::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'birth_date' => $validated['birth_date'],
                    'cin' => $validated['cin'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'address' => $validated['address'],
                ]);
            }

            return User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'client',
                'client_id' => $client->id,
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.dashboard')->with('success', 'Compte client cree avec succes.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
