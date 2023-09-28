@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('invoices.index'),
]) }}" class="dropdown-item"
    aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['user_name'] }}حذف الفاتورة ذات الرقم {{ $data['invoice_number'] }}
    </div>
    {{ $slot }}
</a>
