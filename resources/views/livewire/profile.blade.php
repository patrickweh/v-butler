<div>
    <h1>{{__('Profile')}}</h1>
    <x-toggle lg :left-label="__('Darkmode')" wire:model.defer="dark" />
    <form action="/logout" method="POST">
        @csrf
        <x-button primary type="submit" :label="__('Logout')" />
    </form>
    <h2 class="py-6">{{__('My Favorites')}}</h2>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @foreach(\Illuminate\Support\Facades\Auth::user()->devices?->toArray() as $device)
            <livewire:device :favorites="true" :wire:key="(string)\Illuminate\Support\Str::uuid()" :device="$device" />
        @endforeach
    </ul>
    <h2 class="py-6">{{__('Add Favorites')}}</h2>
    <div class="pb-6">
        <x-input icon="search" wire:model.debounce.500ms="search" :placeholder="__('Search devices')" />
    </div>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-75">
        @foreach($devices as $device)
            <livewire:device :favorites="true" :wire:key="(string)\Illuminate\Support\Str::uuid()" :device="$device" />
        @endforeach
    </ul>
</div>
