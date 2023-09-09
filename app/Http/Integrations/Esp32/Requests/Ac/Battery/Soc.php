<?php

namespace App\Http\Integrations\Esp32\Requests\Ac\Battery;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class Soc extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/battery_soc';
    }
}
