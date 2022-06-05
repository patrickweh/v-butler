<div>
    <div class="flex justify-between">
        <h1>{{__('Devices')}}</h1>
        {{--        <i onclick="$openModal('newDeviceModal')" class="fa-regular fa-plus-circle text-blue-500 cursor-pointer"></i>--}}
        <a href="{{route('devices.edit.id?')}}">
            <i class="fa-regular fa-plus-circle text-blue-500 cursor-pointer"></i>
        </a>
    </div>
    <div class="pb-6">
        <x-input icon="search" wire:model.debounce.500ms="search" :placeholder="__('Search devices')" />
    </div>
    @if($groupedDevices)
        <h2 class="pb-6">{{__('Grouped devices')}}</h2>
        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
            @foreach($groupedDevices as $device)
                <livewire:device wire:key="{{(string)\Illuminate\Support\Str::uuid()}}" :device="$device" />
            @endforeach
        </ul>
    @endif
    <h2 class="pb-6">{{__('Single devices')}}</h2>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @forelse($devices as $device)
            <livewire:device wire:key="{{(string)\Illuminate\Support\Str::uuid()}}" :device="$device" />
        @empty
            <div>{{__('No Devices')}}</div>
        @endforelse
    </ul>
    @if($page < $pages)
        <div class="pt-6">
            <x-button class="w-full" primary :label="__('Load more…')" wire:click="loadMore" />
        </div>
    @endif
</div>
