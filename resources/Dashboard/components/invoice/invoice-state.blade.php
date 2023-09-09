@props(['state'])

<span @class([
    'text-success' => $state == 'paid',
    'text-danger' => $state == 'unPaid',
    'text-warning' => $state == 'partiallyPaid',
])>{{ __($state) }} </span>
