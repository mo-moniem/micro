<?php

namespace App\Http\Middleware;

use App\Services\RabbitMQService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->bearerToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'accept' => 'application/json',
        ])->get(config('microservices.user.url').'/user');

        if($response->status() !== 200){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $request->merge($response->json());
        return $next($request)->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'accept' => 'application/json',
        ]);

    }
}
