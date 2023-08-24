@props(['message'])

@if ($message)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@endif
