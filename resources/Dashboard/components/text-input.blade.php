@props(['disabled' => false, 'hasError' => false])


<input {{ $attributes->whereDoesntStartWith('class') }}
    {{ $attributes->merge(['class' => 'form-control'])->class(['is-invalid' => $hasError]) }}
    {{ $disabled ? 'disabled' : '' }}>
