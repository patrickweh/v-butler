<div class="flex flex-col justify-between h-full">
    <div class="w-full flex items-center justify-between px-6 pt-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <i class="fa-solid {{$device['is_on'] ? 'fa-door-open' : 'fa-door-closed'}}"></i>
                <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
            </div>
        </div>
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <x-button class="w-full" wire:click="on" primary spinner="on" loading-delay="short">
                <x-slot name="label">
                    <i class="fa-solid fa-lock-open"></i> {{__('Open')}}
                </x-slot>
            </x-button>
        </div>
    </div>
</div>

