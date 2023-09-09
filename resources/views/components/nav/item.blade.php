<a x-on:click="activeItem = $el.getAttribute('href')" x-bind:class="activeItem === '{{$href}}' && 'text-blue-400 fill-blue-400'" class="text-gray-500 flex flex-col items-center" href="{{$href}}">
    <x-dynamic-component :component="'phosphor.icons::.regular.'.$icon" class="w-6 h-6" fill=""/>
    <div class="text-xs">
        {{$label}}
    </div>
</a>
