<?php

namespace App\Http\Integrations\Esp32;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class MtecConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return config('mtec.base_url').'/sensor';
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [];
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
}
