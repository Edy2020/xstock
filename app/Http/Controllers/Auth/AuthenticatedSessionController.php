<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\LogActividad;

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

        $user = auth()->user();

        // Expulsar si el Admin lo desactivó
        if ($user->estado === 'inactivo') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors(['email' => 'Tu cuenta ha sido desactivada temporal o permanentemente. Comunícate con Administración.']);
        }

        // Actualizar último acceso de forma silenciosa
        $user->last_login_at = now();
        $user->save();

        $request->session()->regenerate();

        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Login',
            'modulo' => 'Sistema',
            'detalle' => 'Inicio de sesión exitoso',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = auth()->id();

        LogActividad::create([
            'user_id' => $userId,
            'accion' => 'Login', // Keeping the generic badge colour, 'Logout' isn't defined explicitly in DB badge as red but we can say "Logout"
            'modulo' => 'Sistema',
            'detalle' => 'Cierre de sesión',
            'ip_address' => request()->ip(),
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
