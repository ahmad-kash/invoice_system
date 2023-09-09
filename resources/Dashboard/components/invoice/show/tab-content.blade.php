@props(['id', 'show' => false])
<div @class(['tab-pane', 'fade', 'show active' => $show]) id="{{ $id }}" role="tabpanel" aria-labelledby="{{ $id }}-tab">
    {{ $slot }}</div>
