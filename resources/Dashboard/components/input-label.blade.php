@props(['value'])

<label for="email" class="col-md-4 col-form-label text-md-end">
    {{ $value ?? $slot }}
</label>
