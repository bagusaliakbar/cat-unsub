<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role;
        
        // Handle dual-role logic if necessary
        if ($role === 'admin' && $userRole !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($role === 'pengawas' && !in_array($userRole, ['admin', 'pengawas'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($role === 'participant' && !in_array($userRole, ['peserta', 'participant'])) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
