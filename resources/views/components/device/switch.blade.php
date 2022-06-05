
    <div class="w-full flex items-center justify-between p-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <i class="fa-duotone fa-lightbulb text-5xl" @if($device['is_on']) style="--fa-secondary-color: orange;" @endif ></i>
                <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
            </div>
        </div>
        <x-toggle :id="(string)\Illuminate\Support\Str::uuid()" wire:click="toggle" lg wire:model.defer="device.is_on" />
    </div>
