<div>
    <div class="flex justify-between">
        <h1>{{__('Rooms')}}</h1>
        {{--        <i onclick="$openModal('newDeviceModal')" class="fa-regular fa-plus-circle text-blue-500 cursor-pointer"></i>--}}
        <a href="{{route('devices.edit.id?')}}">
            <i class="fa-regular fa-plus-circle text-blue-500 cursor-pointer"></i>
        </a>
    </div>
    <div class="pb-6">
        <x-input icon="search" wire:model.debounce.500ms="search" :placeholder="__('Search rooms')" />
    </div>
        @forelse($rooms as $room)
            <div class="pb-6">
                <div class="w-full flex justify-between p-1">
                    <h2>{{$room['name']}}</h2>
                    <x-nav.room-dropdown :room="$room" />
                </div>
                <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
                @foreach($room['devices'] as $device)
                    <livewire:device :device="$device" />
                @endforeach
                </ul>
            </div>
        @empty
            <div>{{__('No Rooms')}}</div>
        @endforelse
</div>
