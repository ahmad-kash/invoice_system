@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('invoices.show', ['invoice' => $data['invoice_id']]),
]) }}"
    class="dropdown-item" aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['user_name'] }} بأنشاء فاتورة جديدو بالرقم {{ $data['invoice_number'] }}
    </div>
    {{ $slot }}
</a>
