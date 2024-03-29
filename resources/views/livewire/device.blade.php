<x-card class="!p-0 relative">
    <x-spinner wire:loading />
    <div class="h-full" x-data="{device: $wire.$entangle('device', true)}">
        <div class="relative">
            <div class="w-full absolute flex justify-end p-1">
                <x-nav.device-dropdown :device="$device" />
            </div>
            <div class="h-full">
                <x-dynamic-component :component="'device.' . $device['component']" :device="$device" />
            </div>
        </div>
    </div>
</x-card>


