<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Update last login time
        auth()->user()->update(['last_login_at' => now()]);

        // Redirect based on user role and registration status
        $user = auth()->user();

        if ($user->hasRole('jamaah')) {
            // Check if jamaah has completed registration
            if (!$user->hasCompletedRegistration()) {
                return redirect()->intended(route('jamaah.daftar', absolute: false));
            }
            return redirect()->intended(route('jamaah.dashboard', absolute: false));
        } elseif ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($user->hasRole('marketing')) {
            return redirect()->intended(route('marketing.dashboard', absolute: false));
        } elseif ($user->hasRole('pimpinan')) {
            return redirect()->intended(route('pimpinan.dashboard', absolute: false));
        } elseif ($user->hasRole('cs')) {
            return redirect()->intended(route('cs.dashboard', absolute: false));
        } elseif ($user->hasRole('agent')) {
            return redirect()->intended(route('agent.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
