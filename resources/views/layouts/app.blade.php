<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>
    <livewire:scripts/>
    <wireui:scripts />
    <script src="{{ mix('js/echo.js') }}" ></script>
    <script defer src="{{ mix('/js/app.js') }}"></script>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

    </script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <livewire:styles />
</head>
<body class="antialiased dark:bg-black dark:text-gray-100">
    <x-notifications z-index="z-50" />
    <x-dialog z-index="z-40" blur="md" align="center" />
    <main class="p-6 pb-24">
        {{$slot}}
    </main>
    @if(auth()->check())
    <footer class="border-t backdrop-blur-xl fixed bottom-0 pb-6 pt-1 px-5 bg-gray-50 dark:border-t-0 dark:bg-neutral-700 text-center text-gray-300 w-full flex justify-between">
        <x-nav />
    </footer>
    @endif
</body>
</html>
