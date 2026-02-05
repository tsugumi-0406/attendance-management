<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAny
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = $guards ?: ['web', 'admin'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                return $next($request);
            }
        }

        // 未ログイン時はユーザーログインへ
        if (! $request->expectsJson()) {
            return redirect('/login'); // ルート名があるなら route('user.login') でもOK
        }

        abort(401);
    }
}
