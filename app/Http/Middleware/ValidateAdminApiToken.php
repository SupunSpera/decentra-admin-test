<?php

namespace App\Http\Middleware;

use App\Traits\Api\ApiHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateAdminApiToken
{
    use ApiHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the API token is present in the request headers
        $apiToken = $request->header('Authorization');

        if ($apiToken && $this->isValidApiToken($apiToken)) {

            return $next($request); // Token is valid, proceed with the request
        }

        // Token is invalid, return an unauthorized response
        return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }
    /**
     * isValidApiToken
     *
     * @param  mixed $token
     * @return void
     */
    private function isValidApiToken($token)
    {
        return $token === config('path.api_token');
    }
}
