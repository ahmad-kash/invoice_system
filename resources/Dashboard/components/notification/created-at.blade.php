@props(['createdAt'])

<span class="text-muted text-sm">{{ $createdAt->diffForHumans() }}</span>
