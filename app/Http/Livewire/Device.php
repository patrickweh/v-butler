<?php

namespace App\Http\Livewire;

use App\Http\Controllers\DeviceController;
use Livewire\Component;

class Device extends Component
{
    public array $device;
    public bool $favorites = false;
    public bool $small = false;

    public function updatedEvent($data)
    {
        $this->device = $data['device'];
    }

    public function getListeners()
    {
        return [
            "echo:devices.{$this->device['id']},DeviceUpdated" => 'updatedEvent',
        ];
    }

    public function render()
    {
        return view('livewire.device');
    }

    public function toggle()
    {
        $device = \App\Models\Device::query()->whereKey($this->device['id'])->first();
        $ctrl = new DeviceController();

        if ($this->device['is_on']) {
            $ctrl->on($device);
        } else {
            $ctrl->off($device);
        }
        $this->skipRender();
    }

    public function on()
    {
        $device = \App\Models\Device::query()->whereKey($this->device['id'])->first();
        $ctrl = new DeviceController();
        $ctrl->on($device);
    }

    public function off()
    {
        $device = \App\Models\Device::query()->whereKey($this->device['id'])->first();
        $ctrl = new DeviceController();
        $ctrl->off($device);
    }

    public function value(int $value)
    {
        $device = \App\Models\Device::query()->whereKey($this->device['id'])->first();
        $ctrl = new DeviceController();
        $ctrl->value($device, $value);
    }
}
