<?php

namespace App\Http\Integrations\Wibutler\Requests;

use App\Models\Service;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Login extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/login';
    }

    public function defaultBody()
    {
        $service = Service::query()->where('name', 'wibutler')->first();

        return [
            'username' => $service->user,
            'password' => $service->password,
        ];
    }
}
