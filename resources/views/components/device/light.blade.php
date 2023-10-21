<div class="w-full flex items-center justify-between px-6 pt-6 space-x-6"
    x-data="{
        init() {
            $watch('color', value => {
                if (value) {
                    $wire.callMethod('setRGB', value);
                }
            });
        },
        color: null,
    }"
>
    <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
            <label>
                <input x-model="color" type="color" style="width: 1px; height: 1px; background: transparent; border: none;" />
                <x-phosphor.icons::fill.lightbulb x-bind:fill="device.is_on && (color || '#f59e0b')" class="w-10 h-10"/>
            </label>
            <h3 x-bind:class="device.is_group && 'cursor-pointer'" x-on:click="$wire.showSubDevices(device.id)" class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
        </div>
    </div>
    <x-toggle :id="(string)\Illuminate\Support\Str::uuid()" x-on:click="$wire.toggle(); device.is_on = !device.is_on" lg wire:model="device.is_on" />
</div>
<div>
    <div class="-mt-px flex divide-x divide-gray-200 p-6">
        <input id="large-range" wire:change="value($event.target.value)" wire:model.blur="device.value"  type="range" min="0" max="{{$max ?? 100}}" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg dark:bg-gray-700">
    </div>
</div>

