
    <div class="rounded-lg flex flex-col justify-between h-full {{$device['is_on'] ? 'bg-positive-400' : 'bg-negative-400'}}">
        <div class="w-full flex items-center justify-between p-6 space-x-6">
            <div class="flex-1 truncate">
                <div class="flex items-center space-x-3 text-white text-6xl">
                    <i class="fa-solid {{$device['is_on'] ? 'fa-person-walking' : ''}}"></i>
                    <h3 class="font-medium truncate">{{$device['name']}}</h3>
                </div>
            </div>
        </div>
    </div>

