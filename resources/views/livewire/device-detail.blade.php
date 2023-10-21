<div>
    <h2>{{ $deviceDetail['name'] }}</h2>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @forelse($subDevices as $device)
            <livewire:device wire:key="{{(string)\Illuminate\Support\Str::uuid()}}" :device="$device" />
        @empty
            <div>{{__('No Devices')}}</div>
        @endforelse
    </ul>
</div>
