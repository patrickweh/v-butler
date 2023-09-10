<?php

namespace App\Http\Integrations\Wibutler\Auth;

use App\Http\Integrations\Wibutler\Requests\Login;
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
     * @param PendingRequest $pendingRequest
     * @return void
     * @throws \Exception
     */
    public function set(PendingRequest $pendingRequest): void
    {
        if ($pendingRequest->getRequest() instanceof Login) {
            return;
        }

        $response = $pendingRequest->getConnector()->send(new Login());

        if ($response->successful()) {
            $pendingRequest->headers()->add('Authorization', 'Bearer ' . $response->json('sessionToken'));
        } else {
            throw new \Exception('Wibutler Login failed');
        }
    }
}
