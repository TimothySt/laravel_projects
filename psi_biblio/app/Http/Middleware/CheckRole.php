<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Members;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Sprawdź, czy użytkownik jest zalogowany
        if ($user) {
            // Sprawdź, czy użytkownik ma jedną z określonych ról
            foreach ($roles as $role) {
                if ($user->role->name == $role) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
