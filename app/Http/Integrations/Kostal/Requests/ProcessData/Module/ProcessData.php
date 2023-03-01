<?php

namespace App\Http\Integrations\Kostal\Requests\ProcessData\Module;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ProcessData extends Request
{
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    private string $moduleId;

    private array $processDataIds;

    public function __construct(string $moduleId, string|array $processdataIds)
    {
        $this->moduleId = $moduleId;
        $this->processDataIds = (array)$processdataIds;
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/processdata/' . $this->moduleId  . '/' . implode(',', $this->processDataIds);
    }
}
