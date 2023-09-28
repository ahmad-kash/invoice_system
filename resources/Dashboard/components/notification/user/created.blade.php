@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('users.index'),
]) }}" class="dropdown-item"
    aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['auth_user_name'] }} بأنشاء مستخدم جديد بالاسم {{ $data['user_name'] }}
    </div>
    {{ $slot }}
</a>
