<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CORS
{

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Methods', ' GET, POST, PATCH, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length,Accept, Accept-Language, Content-Language, Content-Type,X-Requested-With, Content-Type, Accept, Origin, Authorization, Access-Control-Request-Method, user-request');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
