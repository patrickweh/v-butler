<?php

namespace App\Livewire\Features;

use App\Models\Device;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class DeviceSearch extends Component
{
    public bool $list = false;

    public array $devices = [];

    public string $search = '';

    public function render()
    {
        if ($this->search) {
            $this->devices = Device::search($this->search)->get()->toArray();
        } else {
            $this->devices = [];
        }

        return view('livewire.features.device-search');
    }
}
