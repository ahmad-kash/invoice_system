@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('products.index'),
]) }}" class="dropdown-item"
    aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['user_name'] }} بانشاء منتج جديد بالاسم {{ $data['product_name'] }}
    </div>
    {{ $slot }}
</a>
