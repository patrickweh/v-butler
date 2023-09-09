<?php

namespace App\Livewire;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ToggleDarkMode extends Component
{
    public bool $dark = false;

    public function mount()
    {
        $this->dark = session('dark', false);
    }

    public function updatedDark(bool $enabled): void
    {
        session()->put('dark', $enabled);
    }

    public function render()
    {
        return view('livewire.toggle-dark-mode');
    }
}
