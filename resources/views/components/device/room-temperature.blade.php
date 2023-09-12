<div class="rounded-lg flex flex-col justify-between h-full" x-bind:class="device.is_on ? 'bg-positive-400' : 'bg-negative-400'">
    <div class="w-full flex items-center justify-between p-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="text-gray-500">{{$device['name']}}</div>
            <div class="flex items-center space-x-3">
                <x-phosphor.icons::fill.person-simple-walk x-bind:class="device.is_on && 'animate-bounce'" class="w-10 h-10"/>
                <h1 class="text-white text-6xl font-medium truncate">{{$device['value'] / 100}} °C</h1>
            </div>
        </div>
    </div>
</div>
