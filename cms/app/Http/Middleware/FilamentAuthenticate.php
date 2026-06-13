<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilamentAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        if (! auth()->user()->canAccessFilament()) {
            abort(403, 'No tienes permiso para acceder al panel de administración.');
        }

        return $next($request);
    }
}
