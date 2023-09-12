<?php

namespace App\Http\Middleware;

// app/Http/Middleware/CheckSessionTimeout.php

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!Auth::guard($guards)->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}