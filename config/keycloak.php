<?php

return [
    'server_url' => env('KEYCLOAK_SERVER_URL', 'http://localhost:8080'),
    'realm' => env('KEYCLOAK_REALM', 'master'),
    'client_id' => env('KEYCLOAK_CLIENT_ID', ''),
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET', ''),
    'redirect_uri' => env('KEYCLOAK_REDIRECT_URI', 'http://localhost:3000/callback'),
    'grant_type' => env('KEYCLOAK_GRANT_TYPE', 'client_credentials'),
];
