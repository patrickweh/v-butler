<div class="w-full flex flex-col items-center justify-between p-6 space-x-6">
    <div class="flex-1 truncate w-full">
        <div class="flex items-center space-x-3">
            <h3 class="dark:text-white text-gray-900 text-sm font-medium truncate">{{$device['name']}}</h3>
        </div>
    </div>
    <div class="w-full flex gap-1">
        <x-device.switcher.swticher name="SWT_A" />
        <x-device.switcher.swticher name="SWT_B" />
    </div>
</div>
