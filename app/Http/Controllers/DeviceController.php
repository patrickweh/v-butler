<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isInstanceOf;

class DeviceController extends Controller
{
    public function on(Device $device)
    {
        $devices = $device->is_group ? $this->getDevices($device->children) : [$device];
        dd($devices);
        foreach ($devices as $singleDevice) {
            $ctrl = new $singleDevice->service->controller;
            $ctrl->on($singleDevice);
            $singleDevice->is_on = true;
            $singleDevice->save();
        }
    }

    public function off(Device $device)
    {
        $devices = $device->is_group ? $device->children : [$device];

        foreach ($devices as $singleDevice) {
            $ctrl = new $singleDevice->service->controller;
            $ctrl->off($singleDevice);
            $singleDevice->is_on = false;
            $singleDevice->save();
        }
    }

    public function value(Device $device)
    {
        $ctrl = new $device->service->controller;
        $ctrl->value($device);
    }

    private function getDevices(iterable $devices)
    {
        $flatDevices = [];
        foreach ($devices as $device) {
            if ($device->is_group) {
                $flatDevices[] = $this->getDevices($device->children);
            } else {
                $flatDevices[] = $device;
            }
        }

        return $flatDevices;
    }
}
