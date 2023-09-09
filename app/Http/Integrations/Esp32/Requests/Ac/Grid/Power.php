<?php

namespace App\Http\Integrations\Esp32\Requests\Ac\Grid;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class Power extends Request
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
        return '/ac_smartmeter_w';
    }
}
