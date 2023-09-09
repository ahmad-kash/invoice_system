<div class="table-responsive mt-15">
    <table class="table center-aligned-table mb-0 table-hover" style="text-align:center">
        <thead>
            <tr class="text-dark">
                <th>#</th>
                <th>حالة الدفع</th>
                <th>ملاحظات</th>
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
                    <td>{{ $detail->created_at }}</td>
                    <td>{{ $detail->user->name }}</td>
                </tr>
            @empty
                <h4 class="font-weight-bold text-center">
                    لا يوجد سجل للدفع
                </h4>
            @endforelse
        </tbody>
    </table>
</div>
