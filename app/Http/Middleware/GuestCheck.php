<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class GuestCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use ApiResponseTrait;
    public function handle(Request $request, Closure $next)
    {
        try {
            // Get the JWT token payload
            $payload = JWTAuth::parseToken()->getPayload();

            // Retrieve the `is_guest` claim from the payload
            $isGuestCheck = $payload->get('is_guest');

            // If the user is a guest, return an Unauthorized response
            if ($isGuestCheck === true) {
                return $this->sendResponse(['error' => 'Unauthorized'],'fail', 401);
            }
        } catch (\Exception $e) {
            // Handle missing or invalid tokens
            return response()->json(['error' => 'Token is invalid or missing'], 401);
        }

        return $next($request);
    }
}
