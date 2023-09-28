@props(['notification'])
@php
    use Illuminate\Support\Str;
    if (!function_exists('parseTypeToBladeComponent')) {
        function parseTypeToBladeComponent($type)
        {
            return 'notification.' . Str::replaceFirst('-', '.', Str::kebab(Str::afterLast($type, '\\')));
        }
    }
@endphp
<x-dynamic-component :component="parseTypeToBladeComponent($notification->type)" :data="$notification->data" :id="$notification->id" to="notifications.show">

    <x-notification.created-at :createdAt="$notification->created_at" />
</x-dynamic-component>
