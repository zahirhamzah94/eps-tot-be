<?php

namespace App\Http\Middleware;

use App\Services\KeycloakService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KeycloakAuth
{
    protected KeycloakService $keycloakService;

    public function __construct(KeycloakService $keycloakService)
    {
        $this->keycloakService = $keycloakService;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $this->getTokenFromRequest($request);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $this->keycloakService->validateToken($token);

        if (!$payload) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        // Attach token payload to request
        $request->attributes->set('keycloak_token', $payload);
        $request->attributes->set('keycloak_user_id', $payload['sub'] ?? null);
        $request->attributes->set('keycloak_username', $payload['preferred_username'] ?? null);
        $request->attributes->set('keycloak_email', $payload['email'] ?? null);
        $request->attributes->set('keycloak_roles', $payload['realm_access']['roles'] ?? []);

        return $next($request);
    }

    /**
     * Extract Bearer token from Authorization header.
     */
    protected function getTokenFromRequest(Request $request): ?string
    {
        $header = $request->header('Authorization');

        if (!$header) {
            return null;
        }

        if (!str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return substr($header, 7);
    }
}
