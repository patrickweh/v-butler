<?php

namespace App\Http\Integrations\Wibutler\Auth;

use App\Http\Integrations\Wibutler\Requests\Login;
use Illuminate\Support\Facades\Cache;
use Saloon\Contracts\Authenticator;
use Saloon\Contracts\PendingRequest;

class WibutlerAuthenticator implements Authenticator
{
    public function __construct()
    {
        //
    }

    /**
     * Apply the authentication to the request.
     *
     * @throws \Exception
     */
    public function set(PendingRequest $pendingRequest): void
    {
        if ($pendingRequest->getRequest() instanceof Login) {
            return;
        }

        $token = Cache::get('wibutler_token');
        $response = null;

        if (! $token) {
            $response = $pendingRequest->getConnector()->send(new Login());
        }

        if ($response?->successful()) {
            $sessionToken = $response->json('sessionToken');
            Cache::put('wibutler_token', $sessionToken, 60 * 60 * 24);
        } elseif ($token) {
            $sessionToken = $token;
        } else {
            Cache::delete('wibutler_token');

            throw new \Exception('Wibutler Login failed');
        }

        $pendingRequest->headers()->add('Authorization', 'Bearer '.$sessionToken);
    }
}
