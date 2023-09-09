<?php

namespace App\Http\Integrations\Kostal\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class Finish extends Request implements HasBody
{
    use HasJsonBody;

    public string $serverSignature;

    public string $authMsg;

    public string $clientKey;

    public string $storedKey;

    public string $transactionId;

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    public function __construct(Response $previous)
    {
        $serverNonce = base64_decode($previous->json('nonce'));
        $this->transactionId = base64_decode($previous->json('transactionId'));
        $salt = base64_decode($previous->json('salt'));
        $rounds = $previous->json('rounds');
        $clientNonce = $previous->getRequest()->clientNonce;

        $saltedPassword = hash_pbkdf2(
            'sha256',
            config('kostal.password'),
            $salt,
            $rounds,
            32,
            true
        );
        $this->clientKey = hash_hmac('sha256', 'Client Key', $saltedPassword, true);
        $this->storedKey = hash('sha256', $this->clientKey, true);
        $serverNonce = base64_encode($serverNonce);
        $salt = base64_encode($salt);
        $this->authMsg = "n=user,r=$clientNonce,r=$serverNonce,s=$salt,i=$rounds,c=biws,r=$serverNonce";
        $clientProof = $this->clientKey ^ hash_hmac('sha256', $this->authMsg, $this->storedKey, true);
        $serverKey = hash_hmac('sha256', 'Server Key', $saltedPassword, true);
        $this->serverSignature = hash_hmac('sha256', $this->authMsg, $serverKey, true);

        $this->body()->set([
            'transactionId' => base64_encode($this->transactionId),
            'proof' => base64_encode($clientProof),
        ]);
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/auth/finish';
    }
}
