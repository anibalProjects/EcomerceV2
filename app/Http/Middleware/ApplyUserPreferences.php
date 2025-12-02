<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserPreferences
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Retrieve preferences with defaults
            $tema = $user->getPreference('tema', 'claro');
            $moneda = $user->getPreference('moneda', 'EUR');
            $paginacion = $user->getPreference('paginacion', 12);

            // Share with all views
            View::share('user_tema', $tema);
            View::share('user_moneda', $moneda);

            // Store pagination in session for controllers to access easily
            // or we could use config(['user.pagination' => $paginacion]);
            session(['user_paginacion' => $paginacion]);
        }

        return $next($request);
    }
}
