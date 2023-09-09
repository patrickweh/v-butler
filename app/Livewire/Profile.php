<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
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
