<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        // Kredensial Basic Auth toko bangunan (bisa dipindah ke .env)
        $validUser = env('BASIC_AUTH_USER', 'admin_bangunan');
        $validPass = env('BASIC_AUTH_PASS', 'tokobangunan123');

        if ($username !== $validUser || $password !== $validPass) {
            return response('Unauthorized', 401, [
                'WWW-Authenticate' => 'Basic realm="Toko Bangunan API"'
            ]);
        }

        return $next($request);
    }
}
