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
            'user' => auth()->user(), // Agregar esta línea para incluir el usuario autenticado

        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Obtener el usuario autenticado
        $user = $request->user();
        
        // Verificar si el usuario tiene más de un rol
        $roles = $user->roles()->get();
        if ($roles->count() > 1) {
            // Si tiene múltiples roles, redirigir a la vista de selección de rol
            return redirect()->route('role.select');
        }
        
        // Si solo tiene un rol, establecerlo como activo automáticamente
        if ($roles->count() == 1) {
            session(['active_role' => $roles->first()->name]);
        }

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

    /**
     * Set the active role for the authenticated user.
     */
    public function setActiveRole(Request $request): RedirectResponse
    {
        $role = $request->input('role');
        
        // Verificar que el usuario tenga este rol
        if ($request->user()->hasRole($role)) {
            // Guardar el rol activo en la sesión
            session(['active_role' => $role]);
        }
        
        return redirect()->back();
    }
}
