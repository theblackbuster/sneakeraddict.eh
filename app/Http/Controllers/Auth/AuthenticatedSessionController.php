<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authentifier l'utilisateur
        $request->authenticate();

        // Régénérer la session pour plus de sécurité
        $request->session()->regenerate();

        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
        $request->session()->put('language', $user->language ?? config('app.locale'));

        // Tableau de redirections par rôle
        $redirectRoutes = [
            'vendeur' => 'vendeur.dashboard',
            'admin'   => 'admin.dashboard',
            'client'  => 'dashboard',
        ];

        // Redirection selon le rôle, ou par défaut
        return redirect()->route($redirectRoutes[$user->role] ?? 'dashboard');
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
