<x-dropdown>
    <x-dropdown.item href="{{route('rooms.edit.id?', ['roomModel' => $room['id']])}}" label="{{__('Edit')}}" />
</x-dropdown>
