<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
    try {
        // Tentative d'authentification (throw ValidationException si KO)
        $request->authenticate();

        // Regénération de la session en cas de succès
        $request->session()->regenerate();

        // LOG: connexion réussie
        Log::channel('security')->info('Login OK', [
            'ip'         => $request->ip(),
            'email'      => $request->input('email'),
            'user_id'    => optional($request->user())->id,
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->intended('/');
    } catch (ValidationException $e) {
        // LOG: tentative de connexion échouée
        Log::channel('security')->warning('Login KO', [
            'ip'         => $request->ip(),
            'email'      => $request->input('email'),
            'user_agent' => $request->userAgent(),
            'errors'     => $e->errors(),   // optionnel, pour tracer le type d’erreur
        ]);

        // On relance l’exception pour laisser Laravel gérer le retour formulaire + messages
        throw $e;
    }
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
