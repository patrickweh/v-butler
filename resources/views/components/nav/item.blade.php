<a x-on:click="activeItem = '{{$href}}'" :class="activeItem === '{{$href}}' && 'text-blue-400'" class="text-gray-500" href="{{$href}}">
    <i class="text-xl {{$icon}}"></i>
    <div class="text-xs">
        {{$label}}
    </div>
</a>
