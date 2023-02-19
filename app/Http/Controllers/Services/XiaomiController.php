<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Service;
use MiIO\Factory;

class XiaomiController extends Controller
{
    public function import(Service $service)
    {
        $devices = Device::query()->where('service_id', $service->id)->get();

        foreach ($devices as $device) {
            try {
                $ctrl = Factory::miRobot($device->config['ip'], $device->foreign_id);
                $status = $ctrl->status()->jsonSerialize();

                $device->details = $status;
                $device->is_on = $status['in_cleaning'] ?? false;
                $device->value = $status['battery'] ?? 0;
                $device->save();
            } catch (\Exception $e) {
            }
        }
    }

    public function on(Device $device)
    {
        $ctrl = Factory::miRobot($device->config['ip'], $device->foreign_id);
        $ctrl->start();
    }

    public function off(Device $device)
    {
        $ctrl = Factory::miRobot($device->config['ip'], $device->foreign_id);
        $ctrl->charge();
    }
}
