<?php

namespace App\Livewire;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use WireUi\Traits\Actions;

#[Lazy]
class Dashboard extends Component
{
    use Actions;

    public array $devices = [];

    public string $search = '';

    public function render()
    {
        return view('livewire.dashboard', ['favorites' => Auth::user()->devices?->toArray()]);
    }

    public function updatedSearch()
    {
        if (! $this->search) {
            $this->reset('devices');
        } else {
            $this->devices = Device::search($this->search)->paginate(5)->toArray()['data'];
        }
    }
}
