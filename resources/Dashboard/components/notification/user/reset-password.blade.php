@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('users.index'),
]) }}" class="dropdown-item"
    aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['auth_user_name'] }} باعادة تعين كلمو المرور للمستخدم {{ $data['user_name'] }}
    </div>
    {{ $slot }}
</a>
