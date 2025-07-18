<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckValueInHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $number, $some): Response
    {
        if ($request->header("token") !== "123456") {
            return response()->json(["message" => "Access denied " . $number . " " . $some], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}

// Para poner middleware global lo ponemos en el app de Bootstrap
