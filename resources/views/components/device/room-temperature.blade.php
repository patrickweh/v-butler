<div class="rounded-lg flex flex-col justify-between h-full" x-bind:class="device.is_on ? 'bg-positive-400' : 'bg-negative-400'">
    <div class="w-full flex items-center justify-between p-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="text-gray-500">{{$device['name']}}</div>
            <div class="flex items-center space-x-3">
                <i class="fa-solid {{$device['is_on'] ? 'fa-person-walking' : ''}}"></i>
                <h1 class="text-white text-6xl font-medium truncate">{{$device['value']}} °C</h1>
            </div>
        </div>
    </div>
</div>
