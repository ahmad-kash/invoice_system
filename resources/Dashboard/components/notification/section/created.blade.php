@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('sections.index'),
]) }}" class="dropdown-item"
    aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['user_name'] }} بانشاء قسم جديد بالاسم {{ $data['section_name'] }}
    </div>
    {{ $slot }}
</a>
