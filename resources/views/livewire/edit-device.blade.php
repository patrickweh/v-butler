<div class="space-y-6">
    <x-input wire:model.defer="device.name" :label="__('Name')" :placeholder="__('Device name…')" />
    @if($device['id'] ?? false)
    <div wire:click="favorite({{$device['id']}}, {{(string)!$device['is_favorite']}})" class="p-1 cursor-pointer">
        <i class="text-yellow-500 {{$device['is_favorite'] ? 'fa-solid' : 'fa-regular'}} fa-star"></i> {{__('Favorite')}}
    </div>
    @endif
    <x-select
        :label="__('Select a component')"
        :placeholder="__('Component…')"
        :options="$components"
        wire:model.defer="device.component"
    />
    <h2 class="">{{__('Assigned rooms')}}</h2>
    @foreach($rooms as $room)
        <x-checkbox :id="(string)\Illuminate\Support\Str::uuid()" value="{{$room['id']}}" wire:model="selectedRooms" :label="$room['name']"/>
    @endforeach

    @if($device['is_group'] ?? false)
        <h2 class="">{{__('Assigned devices')}}</h2>
        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @foreach($children as $device)
                <x-checkbox value="{{$device['id']}}" :id="(string)\Illuminate\Support\Str::uuid()" wire:model="selected" :label="$device['name']"/>
        @endforeach
        </ul>
        <x-input wire:model="search" icon="search" />
        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @foreach($devices as $device)
            <x-checkbox value="{{$device['id']}}" :id="(string)\Illuminate\Support\Str::uuid()" wire:model="selected" :label="$device['name']"/>
        @endforeach
        </ul>
    @endif
    <div class="flex justify-between gap-x-4">
        <x-button negative label="{{__('Löschen')}}" onClick="
                            window.$wireui.confirmDialog({
                            title: '{{__('Delete device…')}}',
                            description: '{{__('Do you really want to delete the device?')}}',
                            icon: 'error',
                            accept: {
                                label: '{{__('Delete')}}',
                                method: 'delete'
                            },
                            reject: {
                                label: '{{__('Cancel')}}'
                            }
                        }, '{{$this->id}}')
                " />

        <div class="flex">
            <x-button flat :label="__('Cancel')" href="{{route('devices')}}" />
            <x-button primary :label="__('Save')" wire:click="save" />
        </div>
    </div>
</div>
