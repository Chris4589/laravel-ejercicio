<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    //after midleware
    //actuar despues de la respuesta
    public function handle(Request $request, Closure $next, $header = 'X-name')
    {
        //si se ejecuta antes de next() es un before middleware
        $response = $next($request);//la respuesta viene de next()
        //afte midleware porque se ejecuta despues de que
        //se construya la respuesta $next()

        $response->headers->set($header, config('app.name'));

        return $response;
    }
}
