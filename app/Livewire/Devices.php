<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\Room;
use Livewire\Component;

class Devices extends Component
{
    public array $devices = [];

    public array $groupedDevices = [];

    public string $search = '';

    public int $page = 1;

    public int $pages = 1;

    public ?int $roomId = null;

    public ?int $deviceId = null;

    protected $listeners = ['echo:devices,DeviceCreated' => 'deviceAdded'];

    protected $queryString = ['deviceId'];

    public function mount(?Room $room)
    {
        $this->roomId = $room->id;
        $this->updatedSearch();
    }

    public function render()
    {
        return view('livewire.devices');
    }

    public function loadMore()
    {
        $this->page++;
        $this->updatedSearch();
    }

    public function deviceAdded($data)
    {
        array_unshift($this->devices, $data['device']);
    }

    public function updatedSearch()
    {
        $query = Device::search($this->search);

        if ($this->roomId) {
            $query->whereIn('room_ids', [$this->roomId]);
        }

        $result = $query
            ->paginate(
                perPage: 15,
                page: $this->search ? 1 : $this->page
            )
            ->toArray();

        if ($this->search) {
            $this->devices = $result['data'];
            $this->page = 1;
        } else {
            $this->devices = array_merge($this->devices, $result['data']);
        }

        $this->pages = $result['last_page'];
    }
}
