<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user()->is_admin;

        $unauthorized = [
            'code' => 401,
            'message' => 'Unauthorized'
        ];

        if ($user &&  $user == 1) {
            return $next($request);
        };

        return response()->json(['errors' => $unauthorized]);
    }
}
