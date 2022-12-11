<?php

namespace App\Http\Livewire;

use App\Models\Level;
use App\Models\Room;
use Livewire\Component;

class Rooms extends Component
{
    public string $search = '';
    public array $rooms = [];
    public array $levels = [];

    public int $level = 0;

    public function boot()
    {
        $this->updatedSearch();
    }

    public function mount()
    {
        $this->levels = Level::query()->select('id', 'name')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.rooms');
    }

    public function updatedLevel()
    {
        $this->updatedSearch();
    }

    public function updatedSearch()
    {
        $query = Room::search($this->search);

        if ($this->level > 0) {
            $query->where('level_id', $this->level);
        }

        $rooms = $query->get()->load('groupDevices');

        $this->rooms = $rooms->toArray();
    }
}
