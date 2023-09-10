<div class="w-full flex items-center justify-between p-6 space-x-6">
    <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
            <x-phosphor.icons::fill.lightbulb x-bind:class="device.is_on && 'fill-amber-500'" class="w-10 h-10"/>
            <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
        </div>
    </div>
    <x-toggle :id="(string)\Illuminate\Support\Str::uuid()" x-on:click="$wire.toggle(); device.is_on = !device.is_on" lg wire:model="device.is_on" />
</div>
