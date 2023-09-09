<div class="flex flex-col justify-between h-full">
    <div class="w-full flex items-center justify-between px-6 pt-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <template x-if="device.is_on">
                    <x-phosphor.icons::fill.door-open class="w-10 h-10"/>
                </template>
                <template x-if="!device.is_on">
                    <x-phosphor.icons::fill.door class="w-10 h-10"/>
                </template>
                <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
            </div>
        </div>
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <x-button class="w-full" wire:click="on" primary spinner="on" loading-delay="short">
                <x-slot name="label">
                    <x-phosphor.icons::fill.lock-open class="w-10 h-10"/>
                    {{__('Open')}}
                </x-slot>
            </x-button>
        </div>
    </div>
</div>

