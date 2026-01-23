<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KeycloakService
{
    protected string $serverUrl;
    protected string $realm;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->serverUrl = rtrim(config('keycloak.server_url'), '/');
        $this->realm = config('keycloak.realm');
        $this->clientId = config('keycloak.client_id');
        $this->clientSecret = config('keycloak.client_secret');
    }

    /**
     * Get Keycloak realm public key for token validation.
     */
    public function getPublicKey(): ?string
    {
        return Cache::remember('keycloak_public_key', 3600, function () {
            try {
                $response = Http::get(
                    "{$this->serverUrl}/realms/{$this->realm}/protocol/openid-connect/certs"
                );

                if ($response->successful()) {
                    $keys = $response->json('keys');
                    if (!empty($keys) && is_array($keys)) {
                        return $keys[0]['x5c'][0] ?? null;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Keycloak public key fetch failed: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Validate and decode JWT token from Keycloak.
     */
    public function validateToken(string $token): ?array
    {
        try {
            $parts = explode('.', $token);

            if (count($parts) !== 3) {
                return null;
            }

            $payload = json_decode(
                base64_decode(strtr($parts[1], '-_', '+/')),
                true
            );

            if (!$payload) {
                return null;
            }

            // Check token expiration
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return null;
            }

            // Verify token issuer
            $expectedIssuer = "{$this->serverUrl}/realms/{$this->realm}";
            if (!isset($payload['iss']) || $payload['iss'] !== $expectedIssuer) {
                return null;
            }

            return $payload;
        } catch (\Exception $e) {
            Log::error('Token validation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get access token using client credentials flow.
     */
    public function getClientToken(): ?string
    {
        return Cache::remember('keycloak_client_token', 3600, function () {
            try {
                $response = Http::asForm()->post(
                    "{$this->serverUrl}/realms/{$this->realm}/protocol/openid-connect/token",
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]
                );

                if ($response->successful()) {
                    return $response->json('access_token');
                }
            } catch (\Exception $e) {
                Log::error('Client token fetch failed: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Exchange authorization code for tokens (OAuth2 Authorization Code flow).
     */
    public function exchangeCodeForToken(string $code, string $redirectUri): ?array
    {
        try {
            $response = Http::asForm()->post(
                "{$this->serverUrl}/realms/{$this->realm}/protocol/openid-connect/token",
                [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $code,
                    'redirect_uri' => $redirectUri,
                ]
            );

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Code exchange failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Refresh access token using refresh token.
     */
    public function refreshToken(string $refreshToken): ?array
    {
        try {
            $response = Http::asForm()->post(
                "{$this->serverUrl}/realms/{$this->realm}/protocol/openid-connect/token",
                [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $refreshToken,
                ]
            );

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Revoke a token.
     */
    public function revokeToken(string $token): bool
    {
        try {
            $response = Http::asForm()->post(
                "{$this->serverUrl}/realms/{$this->realm}/protocol/openid-connect/revoke",
                [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'token' => $token,
                ]
            );

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Token revoke failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user info from Keycloak using access token.
     */
    public function getUserInfo(string $accessToken): ?array
    {
        try {
            $response = Http::withToken($accessToken)->get(
                "{$this->serverUrl}/realms/{$this->realm}/protocol/openid-connect/userinfo"
            );

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('User info fetch failed: ' . $e->getMessage());
        }

        return null;
    }
}
