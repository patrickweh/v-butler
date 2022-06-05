<div>
    <h1>{{__('Profile')}}</h1>
    <x-toggle lg :left-label="__('Darkmode')" wire:model.defer="dark" />
    <form action="/logout" method="POST">
        @csrf
        <x-button primary type="submit" :label="__('Logout')" />
    </form>
</div>
