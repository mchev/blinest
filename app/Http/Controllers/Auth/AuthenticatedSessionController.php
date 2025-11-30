<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\UpdateUserLevel;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $user->update([
            'ip' => $request->ip(),
        ]);

        // Update last login date for streak calculation
        $today = now()->toDateString();
        $userLevel = $user->userLevel;
        $shouldUpdateStreak = ! $userLevel || ! $userLevel->last_login_date || $userLevel->last_login_date->toDateString() !== $today;

        // Update user level in queue on login if not updated recently (to catch seniority changes and streak)
        // Only update if last calculation was more than 1 hour ago to avoid unnecessary updates
        // Or if we need to update the streak
        if ($shouldUpdateStreak || ! $userLevel || ! $userLevel->last_calculated_at || $userLevel->last_calculated_at->lt(now()->subHour())) {
            UpdateUserLevel::dispatch($user, now());
        }

        if ($request->isFromModal) {
            return redirect()->back();
        }

        return redirect()->intended(AppServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
