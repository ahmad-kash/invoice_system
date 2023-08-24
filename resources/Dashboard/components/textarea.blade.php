@props(['disabled' => false, 'hasError' => false])
<textarea {{ $attributes }} {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
    {{ $disabled ? 'disabled' : '' }}>{{ $slot }}</textarea>
