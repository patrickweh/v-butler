<div class="w-full flex items-center justify-between px-6 pt-6 space-x-6">
    <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
            <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
        </div>
    </div>
</div>
<div>
    <div class="p-6">
        <x-range-slider max="100" wire:model.blur="device.value" />
    </div>
    <div class="-mt-px flex divide-x divide-gray-200">
        <x-button class="w-full" secondary wire:click="switchOff()" spinner="off" loading-delay="short">
            <x-slot name="label">
                {{__('Close')}}
            </x-slot>
        </x-button>
        <x-button class="w-full" primary wire:click="switchOn()" spinner="on" loading-delay="short">
            <x-slot name="label">
                {{__('Open')}}
            </x-slot>
        </x-button>
    </div>
</div>
