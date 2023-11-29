<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'auth' => env('APP_URL') . 'auth',
            'error' => null
        ]);
    }

    public function redirect_to_google() {
        try {
            return Socialite::driver('google')->redirect();
        } catch(\Exception $e) {
            return Inertia::render('Auth/Login', [
                'auth' => env('APP_URL') . 'auth',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function auth_via_google() {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
            ]);

            \Auth::login($user);

            return redirect()->route('dashboard');
        } catch(\InvalidStateException $e) {
            return Inertia::render('Auth/Login', [
                'auth' => env('APP_URL') . 'auth',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
