<?php

namespace App\Http\Integrations\Kostal;

use App\Http\Integrations\Kostal\Requests\Auth\CreateSession;
use App\Http\Integrations\Kostal\Requests\Auth\Finish;
use App\Http\Integrations\Kostal\Requests\Auth\Start;
use Saloon\Contracts\Authenticator;
use Saloon\Contracts\PendingRequest;

class KostalAuthenticator implements Authenticator
{
    /**
     * @throws \Exception
     */
    public function set(PendingRequest $pendingRequest): void
    {
        if (
            $pendingRequest->getRequest() instanceof Start
            || $pendingRequest->getRequest() instanceof Finish
            || $pendingRequest->getRequest() instanceof CreateSession
        ) {
            return;
        }

        $connector = $pendingRequest->getConnector();

        $start = $connector->send(new Start);
        $finish = $connector->send(new Finish($start));
        $session = $connector->send(new CreateSession($finish));

        $pendingRequest->headers()->add('Authorization', 'Session '.$session->json('sessionId'));
    }
}
