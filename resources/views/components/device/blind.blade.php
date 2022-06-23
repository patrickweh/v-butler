    <div class="w-full flex items-center justify-between px-6 pt-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <i class="fa-solid {{$device['value'] ? ($device['value'] == 100 ? 'fa-blinds' : 'fa-blinds-open') : 'fa-blinds-raised'}}"></i>
                <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
            </div>
        </div>
    </div>
    <div>
        <div class="p-6">
            <x-range-slider max="100" wire:model.lazy="device.value" />
        </div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <x-button class="w-full" secondary wire:click="off" spinner="off" loading-delay="short">
                <x-slot name="label">
                    <i class="fa-solid fa-down"></i> {{__('Close')}}
                </x-slot>
            </x-button>
            <x-button class="w-full" primary wire:click="on" spinner="on" loading-delay="short">
                <x-slot name="label">
                    <i class="fa-solid fa-up"></i> {{__('Open')}}
                </x-slot>
            </x-button>
        </div>
    </div>



