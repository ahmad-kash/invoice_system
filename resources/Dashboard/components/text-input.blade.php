@props(['disabled' => false, 'hasError' => false])

<input {{ $attributes }} {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
    {{ $disabled ? 'disabled' : '' }}>
