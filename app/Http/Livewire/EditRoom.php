<?php

namespace App\Http\Livewire;

use App\Models\Level;
use App\Models\Room;
use Livewire\Component;

class EditRoom extends Component
{
    public array|Room $room = [];

    public array $levels;

    protected $rules = [
        'room.level_id' => 'nullable|exists:levels,id',
        'room.name' => 'required|string',
    ];

    public function mount(?Room $roomModel)
    {
        $this->room = $roomModel->toArray();

        $this->levels = Level::query()->select(['id', 'name'])->get()->toArray();
    }

    public function render()
    {
        return view('livewire.edit-room');
    }

    public function save()
    {
        $validated = $this->validate();
        if ($this->room['id'] ?? false) {
            $room = Room::query()->whereKey($this->room['id'])->firstOrNew();
        } else {
            $room = new Room();
        }
        $room->fill($validated['room']);
        $room->save();

        return redirect()->route('rooms');
    }

    public function delete()
    {
        Room::query()->whereKey($this->room['id'])->first()->delete();

        return redirect()->route('rooms');
    }
}
