<div class="w-full flex items-center justify-between px-6 pt-6 space-x-6">
    <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
            <i x-bind:class="device.is_on && 'fa-spin'" class="fa-solid fa-vacuum-robot"></i>
            <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name'] . ' (' . ($device['details']['state_message'] ?? 'unknown') . ')'}}</h3>
        </div>
    </div>
</div>
<div>
    <div class="p-6">
        <x-range-slider max="100" wire:model.lazy="device.value" />
    </div>
    <div class="-mt-px flex divide-x divide-gray-200">
        @if($device['is_on'])
        <x-button class="w-full" secondary wire:click="off" spinner="off" loading-delay="short">
            <x-slot name="label">
                <i class="fa-solid fa-square"></i> {{__('Stop')}}
            </x-slot>
        </x-button>
        @else
        <x-button class="w-full" primary wire:click="on" spinner="on" loading-delay="short">
            <x-slot name="label">
                <i class="fa-solid fa-play"></i> {{__('Start')}}
            </x-slot>
        </x-button>
        @endif
    </div>
</div>

