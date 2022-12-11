<div class="w-full flex items-center justify-between px-6 pt-6 space-x-6">
    <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
            <i class="fa-duotone fa-lightbulb text-5xl" x-bind:style="device.is_on && '--fa-secondary-color: orange'"></i>
            <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
        </div>
    </div>
    <x-toggle :id="(string)\Illuminate\Support\Str::uuid()" x-on:click="$wire.toggle(); device.is_on = !device.is_on" lg wire:model.defer="device.is_on" />
</div>
<div>
    <div class="-mt-px flex divide-x divide-gray-200 p-6">
        <input id="large-range" wire:change="value($event.target.value)" wire:model.lazy="device.value"  type="range" min="0" max="{{$max ?? 100}}" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg dark:bg-gray-700">
    </div>
</div>

