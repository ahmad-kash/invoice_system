@props(['value'])

<label {{ $attributes }} class="col-form-label text-md-end">
    {{ $value ?? $slot }}
</label>
