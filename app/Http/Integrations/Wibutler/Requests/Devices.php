<?php

namespace App\Http\Integrations\Wibutler\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class Devices extends Request
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
        return '/devices';
    }
}
