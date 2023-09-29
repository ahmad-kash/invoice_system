<div class="table-responsive mt-15">
    <table class="table center-aligned-table mb-0 table-hover" style="text-align:center">
        <thead>
            <tr class="text-dark">
                <th>#</th>
                <th>حالة الدفع</th>
                <th>ملاحظات</th>
                <th>القيمة المدفوعة</th>
                <th>تاريخ الاضافة </th>
                <th>المستخدم</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paymentDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><x-invoice.invoice-state :state="$detail->state->label()" /></td>
                    <td>{{ $detail->note }}</td>
                    <td>{{ $detail->amount }}</td>
                    <td>{{ $detail->created_at }}</td>
                    <td>{{ $detail->user->name }}</td>
                </tr>
            @empty
                <h4 class="font-weight-bold text-center">
                    لا يوجد سجل للدفع
                </h4>
            @endforelse
            @if ($paymentDetails->isNotEmpty())
                <tr>
                    <td class="text-primary">اجمالي المدفوعات</td>
                    <td><x-invoice.invoice-state :state="$paymentDetails->last()->state->label()" /></td>
                    <td></td>
                    <td>{{ number_format($paymentDetails->sum('amount'), 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
