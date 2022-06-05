<?php

namespace App\Http\Livewire;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public bool $dark;
    public array $devices = [];
    public string $search = '';

    public function boot()
    {
        $this->dark = Auth::user()->is_dark_mode;
    }

    public function render()
    {
        return view('livewire.profile');
    }

    public function updatedSearch()
    {
        if (!$this->search) {
            $this->reset('devices');
        } else {
            $this->devices = Device::search($this->search)->paginate(5)->toArray()['data'];
        }
    }
}
