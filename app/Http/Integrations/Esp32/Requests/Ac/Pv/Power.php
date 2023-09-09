<?php

namespace App\Http\Integrations\Esp32\Requests\Ac\Pv;

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
        return '/power_ac_tot_w_pv';
    }
}
