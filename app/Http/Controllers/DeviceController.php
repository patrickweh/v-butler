<?php

namespace App\Http\Controllers;

use App\Models\Device;

class DeviceController extends Controller
{
    public function on(Device $device)
    {
        $devices = $device->allDescendants()
            ->push($device)
            ->filter(fn($device) => ! $device->is_group && $device->service);

        $parents = [];
        foreach ($devices as $singleDevice) {
            $ctrl = new $singleDevice->service?->controller;

            if (! $ctrl) {
                continue;
            }

            $ctrl->on($singleDevice);
            $singleDevice->is_on = true;
            $singleDevice->save();

            $parents = array_merge($parents, $singleDevice->parent?->pluck('id')->toArray());
        }

        $this->updateParents($parents, ['is_on' => true]);
    }

    public function off(Device $device)
    {
        $devices = $device->allDescendants()
            ->push($device)
            ->filter(fn($device) => ! $device->is_group && $device->service);

        $parents = [];
        foreach ($devices as $singleDevice) {
            $ctrl = new $singleDevice->service?->controller;
            if (! $ctrl) {
                continue;
            }

            $ctrl->off($singleDevice);
            $singleDevice->is_on = false;
            $singleDevice->save();

            $parents = array_merge($parents, $singleDevice->parent?->pluck('id')->toArray());
        }

        $this->updateParents($parents, ['is_on' => false]);
    }

    public function value(Device $device, int $value)
    {
        $devices = $device->allDescendants()
            ->push($device)
            ->filter(fn($device) => ! $device->is_group && $device->service);

        $parents = [];
        foreach ($devices as $singleDevice) {
            $ctrl = new $singleDevice->service->controller;
            $ctrl->value($singleDevice, $value);
            $singleDevice->value = $value;
            $singleDevice->save();

            $parents = array_merge($parents, $singleDevice->parent?->pluck('id')->toArray());
        }

        $this->updateParents($parents, ['value' => $value]);
    }

    private function getDevices(iterable $devices)
    {
        $flatDevices = collect();
        foreach ($devices as $device) {
            if ($device->is_group) {
                $flatDevices->merge($this->getDevices($device->children));
            } else {
                $flatDevices->add($device);
            }
        }

        return $flatDevices;
    }

    private function updateParents(array $parentIds, array $values)
    {
        $parentDevices = Device::query()->whereIntegerInRaw('id', array_unique($parentIds))->get();

        if (! count($parentDevices)) {
            return;
        }

        foreach ($parentDevices as $parentDevice) {
            $parentDevice->fill($values);
            $parentDevice->save();
        }
    }
}
