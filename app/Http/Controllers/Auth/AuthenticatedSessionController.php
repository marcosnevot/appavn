<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
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
        $request->authenticate();

        $remember = $request->has('remember');

        if ($remember) {
            // Guardar el nombre en una cookie si selecciona "Recordarme"
            Cookie::queue('remember_name', $request->name, 43200); // 30 días
            // Guardar el estado del checkbox "remember" en una cookie
            Cookie::queue('remember_me', '1', 43200); // 30 días
        } else {
            // Borrar la cookie si no se selecciona "Recordarme"
            Cookie::queue(Cookie::forget('remember_name'));
            Cookie::queue(Cookie::forget('remember_me'));
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
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
