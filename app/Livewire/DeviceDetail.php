<?php

namespace App\Livewire;

use Livewire\Component;

class DeviceDetail extends Component
{
    public array $deviceDetail;

    public array $subDevices;

    public function mount(\App\Models\Device $device)
    {
        $this->deviceDetail = $device->toArray();

        $this->subDevices = $device->allDescendants()
            ->filter(fn($subDevice) => ! $subDevice->is_group && $subDevice->service)
            ->toArray();
    }

    public function render()
    {
        return view('livewire.device-detail');
    }
}
