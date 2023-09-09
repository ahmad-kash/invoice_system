<div class="table-responsive mt-15">
    <table class="table table-striped" style="text-align:center">
        <tbody>
            <tr>
                <th scope="row">رقم الفاتورة</th>
                <td>{{ $invoice->number }}</td>
                <th scope="row">تاريخ الاصدار</th>
                <td>{{ $invoice->create_date }}</td>
                <th scope="row">تاريخ الاستحقاق</th>
                <td>{{ $invoice->due_date }}</td>
                <th scope="row">القسم</th>
                <td>{{ $invoice->sectionName }}</td>
            </tr>

            <tr>
                <th scope="row">المنتج</th>
                <td>{{ $invoice->productName }}</td>
                <th scope="row">مبلغ التحصيل</th>
                <td>{{ $invoice->collection_amount }}</td>
                <th scope="row">مبلغ العمولة</th>
                <td>{{ $invoice->commission_amount }}</td>
                <th scope="row">الخصم</th>
                <td>{{ $invoice->discount }}</td>
            </tr>


            <tr>
                <th scope="row">نسبة الضريبة</th>
                <td>{{ $invoice->VAT_rate }}</td>
                <th scope="row">قيمة الضريبة</th>
                <td>{{ $invoice->VAT_value }}</td>
                <th scope="row">الاجمالي مع الضريبة</th>
                <td>{{ $invoice->total }}</td>
                <th scope="row">الحالة الحالية</th>
                <td><x-invoice.invoice-state :state="$invoice->state->label()" /></td>

            </tr>

            <tr>
                <th scope="row">ملاحظات</th>
                <td>{{ $invoice->note }}</td>
            </tr>
        </tbody>
    </table>
</div>
