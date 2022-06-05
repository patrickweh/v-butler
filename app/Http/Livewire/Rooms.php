<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Livewire\Component;

class Rooms extends Component
{
    public string $search = '';
    public array $rooms = [];

    public function boot()
    {
        $this->updatedSearch();
    }

    public function render()
    {
        return view('livewire.rooms');
    }

    public function updatedSearch()
    {
        $this->rooms = Room::search($this->search)->get()->load('devices')->toArray();
    }
}
