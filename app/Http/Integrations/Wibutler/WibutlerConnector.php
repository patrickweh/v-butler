<?php

namespace App\Http\Integrations\Wibutler;

use App\Http\Integrations\Wibutler\Auth\WibutlerAuthenticator;
use App\Models\Service;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class WibutlerConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        $service = Service::query()->where('name', 'wibutler')->first();

        return \Str::finish($service->url, '/').'api';
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [];
    }

    public function defaultAuth(): WibutlerAuthenticator
    {
        return new WibutlerAuthenticator();
    }
}
