<?php

namespace App\Http\Integrations\Kostal\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Start extends Request implements HasBody
{
    use HasJsonBody;

    public string $clientNonce;

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    public function __construct()
    {
        $this->clientNonce = base64_encode(random_bytes(12));
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/auth/start';
    }

    public function defaultBody(): array
    {
        return [
            'username' => 'user',
            'nonce' => $this->clientNonce,
        ];
    }
}
