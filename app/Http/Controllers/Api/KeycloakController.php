<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KeycloakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KeycloakController extends Controller
{
    protected KeycloakService $keycloakService;

    public function __construct(KeycloakService $keycloakService)
    {
        $this->keycloakService = $keycloakService;
    }

    /**
     * Redirect to Keycloak login page.
     */
    public function loginRedirect()
    {
        $clientId = config('keycloak.client_id');
        $redirectUri = config('keycloak.redirect_uri');
        $serverUrl = config('keycloak.server_url');
        $realm = config('keycloak.realm');

        $authUrl = "{$serverUrl}/realms/{$realm}/protocol/openid-connect/auth?" . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => bin2hex(random_bytes(16)),
        ]);

        return response()->json(['url' => $authUrl]);
    }

    /**
     * Exchange authorization code for access token.
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');
        $state = $request->query('state');

        if (!$code) {
            return response()->json(['error' => 'Missing authorization code'], 400);
        }

        $tokens = $this->keycloakService->exchangeCodeForToken(
            $code,
            config('keycloak.redirect_uri')
        );

        if (!$tokens) {
            return response()->json(['error' => 'Failed to exchange code for tokens'], 401);
        }

        return response()->json($tokens);
    }

    /**
     * Refresh access token using refresh token.
     */
    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $tokens = $this->keycloakService->refreshToken($request->input('refresh_token'));

        if (!$tokens) {
            return response()->json(['error' => 'Failed to refresh token'], 401);
        }

        return response()->json($tokens);
    }

    /**
     * Logout and revoke token.
     */
    public function logout(Request $request)
    {
        $token = $this->extractToken($request);

        if ($token) {
            $this->keycloakService->revokeToken($token);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get current user info from Keycloak token.
     */
    public function userInfo(Request $request)
    {
        $token = $this->extractToken($request);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userInfo = $this->keycloakService->getUserInfo($token);

        if (!$userInfo) {
            return response()->json(['error' => 'Failed to fetch user info'], 401);
        }

        return response()->json($userInfo);
    }

    /**
     * Extract Bearer token from request.
     */
    protected function extractToken(Request $request): ?string
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return substr($header, 7);
    }
}