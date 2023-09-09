<div class="rounded-lg flex flex-col justify-between h-full" x-bind:class="device.is_on ? 'bg-positive-400' : 'bg-negative-400'">
    <div class="w-full flex items-center justify-between p-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3 text-white text-6xl">
                <x-phosphor.icons::fill.person-simple-walk x-bind:class="device.is_on && 'animate-bounce'" class="w-10 h-10"/>
                <h3 class="font-medium truncate">{{$device['name']}}</h3>
            </div>
        </div>
    </div>
</div>
