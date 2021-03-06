<?php

namespace App\Http\Livewire;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public bool $dark;

    public function boot()
    {
        $this->dark = Auth::user()->is_dark_mode;
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
