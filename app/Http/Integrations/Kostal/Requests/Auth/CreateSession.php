<?php

namespace App\Http\Integrations\Kostal\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateSession extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    public function __construct(Response $previous)
    {
        $token = $previous->json('token');
        $signature = base64_decode($previous->json('signature'));
        $serverSignature = $previous->getRequest()->serverSignature;
        $authMsg = $previous->getRequest()->authMsg;
        $clientKey = $previous->getRequest()->clientKey;
        $storedKey = $previous->getRequest()->storedKey;
        $transactionId = $previous->getRequest()->transactionId;

        if ($signature !== $serverSignature) {
            throw new \Exception('Invalid signature');
        }

        $protocolKey = hash_hmac('sha256', 'Session Key'.$authMsg.$clientKey, $storedKey, true);
        $sessionNonce = random_bytes(16);
        $cipher = openssl_encrypt($token, 'aes-256-gcm', $protocolKey, OPENSSL_RAW_DATA, $sessionNonce, $authTag);

        $this->body()->set([
            // AES initialization vector
            'iv' => base64_encode($sessionNonce),
            // AES GCM tag
            'tag' => base64_encode($authTag),
            // ID of authentication transaction
            'transactionId' => base64_encode($transactionId),
            // Only the token or token and service code (separated by colon). Encrypted with
            // AES using the protocol key
            'payload' => base64_encode($cipher),
        ]);
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/auth/create_session';
    }
}
