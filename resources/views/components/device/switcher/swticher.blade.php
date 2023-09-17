<div class="flex flex-col w-full gap-1">
    <x-button icon="chevron-up" primary wire:click="switchOn('{{ $name ?? null }}')">
        {{ __('On') }}
    </x-button>
    <x-button icon="chevron-down" secondary wire:click="switchOff('{{ $name ?? null }}')">
        {{ __('Off') }}
    </x-button>
</div>
