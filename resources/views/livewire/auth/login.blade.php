<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <x-logo />
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{__('Sign in to your account')}}</h2>
        </div>
        @if(App::isLocal() || request()->ip() === request()->server('SERVER_ADDR'))
            <x-login-link />
        @endif
        <form class="mt-8 space-y-6" action="{{route('login')}}" method="POST">
            @csrf
            <x-input :placeholder="__('Email address')" wire:model.live="email"/>
            <x-input type="password" :placeholder="__('Password')" wire:model.live="password"/>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <x-checkbox id="right-label" :label="__('Remember me')" wire:model.live="remember" />
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">{{__('Forgot your password?')}}</a>
                </div>
            </div>
             <x-button type="submit" class="w-full" icon="login" primary :label="__('Sign in')"/>
        </form>
    </div>
</div>
