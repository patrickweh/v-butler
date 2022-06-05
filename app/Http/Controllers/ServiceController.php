<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    protected array $rules = [
        'name' => 'required|string|unique:services,name',
        'url' => 'sometimes|required|string|nullable',
        'user' => 'sometimes|required|string|nullable',
        'password' => 'sometimes|required|string|nullable',
        'token' => 'sometimes|required|string|nullable',
        'config' => 'sometimes|required|array|nullable',
    ];

    public function create(array $data): array
    {
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return ResponseHelper::createArrayResponse(statusCode: 422, data: $validator->errors()->toArray());
        }

        $validated = $validator->validated();

        $service = new Service();
        $service->fill($validated);

        return ResponseHelper::ok('Service created', $service->toArray());
    }
}
