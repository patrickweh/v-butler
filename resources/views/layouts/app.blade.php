<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="{{asset('manifest.json')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>
    <livewire:scripts/>
    <wireui:scripts />
    @vite('resources/js/features/echo.js')
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    <livewire:styles />
</head>
<body class="antialiased bg-slate-50 dark:bg-black dark:text-gray-100">
    <x-notifications z-index="z-50" />
    <x-dialog z-index="z-40" blur="md" align="center" />
    <main class="p-6 pb-24">
        {{$slot}}
    </main>
    @if(auth()->check())
    <footer x-data="{activeItem: window.location.toString()}" id="footer-nav" data-turbo-permanent class="border-t backdrop-blur-xl fixed bottom-0 pb-6 pt-1 px-5 bg-gray-100/95 dark:border-t-0 dark:bg-neutral-700/95 text-center text-gray-300 w-full flex justify-between">
        <x-nav />
    </footer>
    @endif
</body>
</html>
