@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('roles.index'),
]) }}" class="dropdown-item"
    aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['user_name'] }} بحذف الدور {{ $data['role_name'] }}
    </div>
    {{ $slot }}
</a>
