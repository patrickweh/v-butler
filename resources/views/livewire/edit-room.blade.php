<div class="space-y-6">
    <x-input wire:model.defer="room.name" :label="__('Name')" :placeholder="__('Room name…')" />
    <x-select
        :label="__('Select a level')"
        :placeholder="__('Level…')"
        :options="$levels"
        option-value="id"
        option-label="name"
        wire:model.defer="room.level_id"
    />
    <div class="flex justify-between gap-x-4">
        <x-button negative label="{{__('Löschen')}}" onClick="
                            window.$wireui.confirmDialog({
                            title: '{{__('Delete room…')}}',
                            description: '{{__('Do you really want to delete the room?')}}',
                            icon: 'error',
                            accept: {
                                label: '{{__('Delete')}}',
                                method: 'delete'
                            },
                            reject: {
                                label: '{{__('Cancel')}}'
                            }
                        }, '{{$this->id}}')
                " />

        <div class="flex">
            <x-button flat :label="__('Cancel')" href="{{route('rooms')}}" />
            <x-button primary :label="__('Save')" wire:click="save" />
        </div>
    </div>
</div>
