<?php

namespace App\Http\Integrations\Esp32\Requests\Ac\Battery;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class Soc extends Request
{
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/battery_soc';
    }
}
