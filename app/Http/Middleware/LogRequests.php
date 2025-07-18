<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = [
            "url" => $request->fullUrl(),
            "ip" => $request->ip(),
            "method" => $request->method(),
            "headers" => $request->headers->all(),
            "body" => $request->getContent()
        ];

        //dd($data);
        Log::info("Request recieved:", $data);

        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        Log::info("Response sent:", [
            "status" => $response->getStatusCode(),
            "content" => $response->getContent()
        ]);
    }
}
