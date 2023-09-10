<div>
    <div class="flex justify-between">
        <h1>{{__('Rooms')}}</h1>
        {{--        <i onclick="$openModal('newDeviceModal')" class="fa-regular fa-plus-circle text-blue-500 cursor-pointer"></i>--}}
        <a href="{{route('devices.edit.id?')}}">
            <x-phosphor.icons::regular.plus-circle class="fill-blue-500 cursor-pointer w-6 h-6" />
        </a>
    </div>
    <x-input icon="search" wire:model.live.debounce.500ms="search" :placeholder="__('Search rooms')" />
    <div class="pb-6">
        <div class="border-b border-gray-200" wire:ignore>
            <nav class="-mb-px flex gap-x-8" x-data="{levels: $wire.$entangle('levels', true), activeLevel: $wire.$entangle('level', true)}">
                <a href="#"
                   x-on:click.prevent="activeLevel = 0"
                   x-bind:class="{ 'border-blue-500 text-blue-600': activeLevel === 0, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeLevel !== 0 }"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">{{ __('All') }}</a>
                <template x-for="level in levels">
                    <a href="#"
                        x-on:click.prevent="activeLevel = level.id"
                       x-bind:class="{ 'border-blue-500 text-blue-600': activeLevel === level.id, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeLevel !== level.id }"
                       x-text="level.name"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"></a>
                </template>
            </nav>
        </div>
    </div>
    @forelse($rooms as $room)
            <div class="pb-6">
                <div class="w-full flex justify-between p-1">
                    <h2>
                        <a href="{{route('devices', ['room' => $room['id']])}}">
                            {{$room['name']}}
                        </a>
                    </h2>
                    <x-nav.room-dropdown :room="$room" />
                </div>
                <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
                @foreach($room['group_devices'] as $device)
                    <livewire:device :device="$device" wire:key="{{ uniqid() }}"/>
                @endforeach
                </ul>
            </div>
        @empty
            <div>{{__('No Rooms')}}</div>
        @endforelse
</div>
