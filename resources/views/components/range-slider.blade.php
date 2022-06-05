<style>
    @media screen and (-webkit-min-device-pixel-ratio:0) {
        input[type='range'] {
            -webkit-appearance: none;
        }

        input[type='range']::-webkit-slider-runnable-track {
            -webkit-appearance: none;
            color: #13bba4;
            margin-top: -1px;
        }

        input[type='range']::-webkit-slider-thumb {
            width: 0px;
            -webkit-appearance: none;
            height: 0px;
            box-shadow: -408px 0 0 408px #43e5f7;
        }

    }
</style>
<input class="shadow-slate-200 overflow-hidden w-full appearance-none h-6 rounded-xl bg-gray-300" type="range" {{$attributes}} min="0" max="{{$max ?? 100}}">
