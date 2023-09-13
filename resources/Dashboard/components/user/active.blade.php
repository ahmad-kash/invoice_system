@props(['active'])

@if ($active)
    <span class="label text-success d-flex">
        <div class="dot-label bg-success ml-1"></div> مفعل
    </span>
@else
    <span class="label text-danger d-flex">
        <div class="dot-label bg-danger ml-1"></div> غير مفعل
    </span>
@endif
