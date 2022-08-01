<div>
    <h1>{{__('Profile')}}</h1>
    <livewire:toggle-dark-mode />
    <form action="/logout" method="POST">
        @csrf
        <x-button primary type="submit" :label="__('Logout')" />
    </form>
</div>
