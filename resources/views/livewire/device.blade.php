<li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200 dark:bg-gray-900">
    <div class="h-full">
        <div class="relative">
            <div class="w-full absolute flex justify-end p-1">
                <x-nav.device-dropdown :device="$device" />
            </div>
            <div>
                <x-dynamic-component :component="'device.' . $device['component']" :device="$device" />
            </div>
        </div>
    </div>
</li>


