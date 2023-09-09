<div>
    <h1>{{__('Home')}}</h1>
    <div class="pb-6">
        <x-input autocorrect="off" autocapitalize="off" spellcheck="false" icon="search" wire:model.live.debounce.500ms="search" :placeholder="__('Search devices')" />
    </div>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @foreach($devices as $device)
            <livewire:device :wire:key="(string)\Illuminate\Support\Str::uuid()" :device="$device" />
        @endforeach
    </ul>
    <h2 class="pb-6">{{__('Favorites')}}</h2>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @foreach($favorites as $device)
            <livewire:device wire:key="{{(string)\Illuminate\Support\Str::uuid()}}" :device="$device" />
        @endforeach
    </ul>
</div>
