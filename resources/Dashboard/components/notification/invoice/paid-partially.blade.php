@props(['data', 'id', 'to'])

<a href="{{ route($to, [
    'notification' => $id,
    'url' => route('invoices.show', ['invoice' => $data['invoice_id']]),
]) }}"
    class="dropdown-item" aria-expanded="false" style="width:18rem;white-space:normal;">
    <div>
        قام المستخدم {{ $data['user_name'] }}بدفع جزئي للفاتورة رقم {{ $data['invoice_number'] }}بالملبغ
        {{ $data['amount'] }}
    </div>
    {{ $slot }}
</a>
