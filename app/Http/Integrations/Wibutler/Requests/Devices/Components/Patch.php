<?php

namespace App\Http\Integrations\Wibutler\Requests\Devices\Components;

use App\Models\Device;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Patch extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(public Device $device, public string $component, array $body = null)
    {
        if ($body) {
            $this->body()->set($body);
        }
    }

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::PATCH;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/devices/'.$this->device->foreign_id.'/components/'.$this->component;
    }
}
