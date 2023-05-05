<?php

namespace App\Http\Controllers\Services;

use App\Helpers\Smart1XMLRPCClient;
use App\Http\Controllers\Controller;
use App\Models\Device;

class Smart1Controller extends Controller
{
    public function import()
    {
    }

    private function runCommand(Device $device)
    {
        $sensorName = $device->foreign_id;
        $client = new Smart1XMLRPCClient($device->service->url);

        if ($deviceType->name == 'powerconsumption') {
            $response = $client->getCounters($service->password);
        } else {
            $response = $client->getSensors($service->password);
        }

        $color = null;
        $stateJson = [
            'Unit' => $response['Reply'][$sensorName]['Unit'],
            'Name' => $response['Reply'][$sensorName]['Name'],
            'Value' => $response['Reply'][$sensorName]['Current_Value'],
            'Color' => $color,
        ];

        // update externalJson
        $response = $response['Reply'][$sensorName];

        $device->externalJson = $response;
        $device->stateJson = $stateJson;
        $device->save();

        return $response;
    }
}
