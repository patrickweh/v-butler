{{-- bg-color-range-0 --}}
{{-- bg-color-range-1 --}}
{{-- bg-color-range-2 --}}
{{-- bg-color-range-3 --}}
{{-- bg-color-range-4 --}}
{{-- bg-color-range-5 --}}
{{-- bg-color-range-6 --}}
{{-- bg-color-range-7 --}}
{{-- bg-color-range-8 --}}
{{-- bg-color-range-9 --}}
{{-- bg-color-range-10 --}}
@if($small ?? false)
    {{$device['value']}}
@else
<div class="rounded-lg flex flex-col justify-between h-full {{percent_to_color(
    $device['value'],
    $device['config']['min'],
    $device['config']['max'],
    )}}">
    <div class="w-full flex items-center justify-between p-6 space-x-6">
        <div class="flex-1 truncate">
            <div class="text-gray-500">{{$device['name']}} </div>
            <div class="flex items-center space-x-3">
                <i class="fa-solid {{$device['is_on'] ? 'fa-person-walking' : ''}}"></i>
                <h1 class="text-white text-6xl font-medium truncate">{{$device['value'] ?? 0}} {{$device['config']['unit'] ?? 'W'}}</h1>
            </div>
        </div>
    </div>
</div>
@endif
