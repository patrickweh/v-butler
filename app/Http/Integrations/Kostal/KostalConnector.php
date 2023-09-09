<?php

namespace App\Http\Integrations\Kostal;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class KostalConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return config('kostal.url').'/api/v1/';
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept-Language' => 'en_US',
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [
            'verify' => false,
            'timeout' => 10,
        ];
    }

    public function defaultAuth(): KostalAuthenticator
    {
        return new KostalAuthenticator();
    }
}
