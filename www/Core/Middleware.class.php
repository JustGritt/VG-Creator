<?php

namespace App\Core;

use App\Utils\Utils;
use Closure;

class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Request  $request
     * @return mixed
     */
    public function handle($request)
    {
        $router = Router::getInstance();
        // var_dump($request);

        // if (!Security::isLoggedIn()) {

        //     die;
        // }
        // $user = $request->user();
        // if ($user && $user->role === 'admin') {
        //     return $next($request);
        // }
        return  Security::isLoggedIn();
    }
}
