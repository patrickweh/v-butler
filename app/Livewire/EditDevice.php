<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class EditDevice extends Component
{
    public array $device = [];

    public array $components = [];

    public array $rooms = [];

    public array $devices = [];

    public array $children = [];

    public string $search = '';

    public array $selected = [];

    public array $selectedRooms = [];

    protected $rules = [
        'device.component' => 'required|string',
        'device.name' => 'required|string',
    ];

    public function boot()
    {
        $components = File::files(resource_path('views/components/device'));
        foreach ($components as $component) {
            $this->components[] = $component->getBasename('.blade.php');
        }

        $this->rooms = Room::all()->toArray();
    }

    public function mount(int $id = null)
    {
        if ($id) {
            $device = Device::query()->whereKey($id)->firstOrFail();
            $this->device = $device->toArray();
            $this->children = $device->children->toArray();
            $this->selected = Arr::pluck($this->children, 'id');

            $this->selectedRooms = Arr::pluck($device->rooms->toArray(), 'id');
        } else {
            $this->device = (new Device(['is_group' => true, 'component' => null]))->toArray();
        }
    }

    public function render()
    {
        return view('livewire.edit-device');
    }

    public function save()
    {
        $this->validate();
        if ($this->device['id'] ?? false) {
            $device = Device::query()->whereKey($this->device['id'])->firstOrNew();
        } else {
            $device = new Device();
        }

        $device->fill($this->device);
        $device->save();
        $device->children()->sync(Arr::pluck($this->children, 'id'));
        $device->rooms()->sync(array_values($this->selectedRooms));

        return redirect()->route('devices');
    }

    public function updatedSearch()
    {
        $result = Device::search($this->search)->paginate(50)->toArray();
        if ($this->search) {
            $this->devices = $result['data'];
        } else {
            $this->devices = [];
        }
    }

    public function updatedSelected($id)
    {
        $this->children = Device::query()->whereIntegerInRaw('id', array_values($id))->get()->toArray();
    }

    public function favorite($id, bool $attach = false)
    {
        if ($attach) {
            Auth::user()->devices()->attach($id);
        } else {
            Auth::user()->devices()->detach($id);
        }

        $this->device['is_favorite'] = ! $this->device['is_favorite'];
    }

    public function delete()
    {
        Device::query()->whereKey($this->device['id'])->first()->delete();

        return redirect()->route('devices');
    }
}
