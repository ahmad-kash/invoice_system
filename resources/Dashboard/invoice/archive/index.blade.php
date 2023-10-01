<x-layouts.app>

    <x-slot:title>ارشيف الفواتير</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">ارشيف الفواتير</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableTop>
            <div class="flex">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filters">بحث</button>
            </div>
            <x-invoice.filter :url="route('invoices.archive.index')"></x-invoice.filter>
        </x-slot:tableTop>
        <x-slot:tableHeader>
            <th>#</th>
            <th>رقم الفاتورة</th>
            <th>تاريخ الفاتورة</th>
            <th>تاريخ الاستحقاق</th>
            <th>المنتج</th>
            <th>القسم</th>
            <th>الخصم</th>
            <th>نسبة الضريبة</th>
            <th>قيمة الضريبة</th>
            <th>الاجمالي</th>
            <th>الحالة</th>
            <th>ملاحظات</th>
            <th>العمليات</th>
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->payment_date }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>{{ $invoice->productName }}</td>
                    <td>{{ $invoice->sectionName }}</td>
                    <td>{{ $invoice->discount }}</td>
                    <td>{{ $invoice->VAT_value }}</td>
                    <td>{{ $invoice->VAT_value }}</td>
                    <td>{{ $invoice->total }}</td>
                    <td>
                        <x-invoice.invoice-state :state="$invoice->state->label()" />
                    </td>

                    <td>{{ $invoice->note }}</td>
                    <td>
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true"
                                class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                                type="button">العمليات</button>
                            <div class="dropdown-menu tx-13">
                                @can('restore invoice')
                                    <a class="dropdown-item restore" href="#" data-id="{{ $invoice->id }}"
                                        data-number="{{ $invoice->number }}" data-toggle="modal"
                                        data-target="#restore_invoice"><i class="text-info fas fa-trash-restore"></i>
                                        استعادة</a>
                                    <form class="d-none" method="POST"
                                        action="{{ route('invoices.restore', ['invoice' => $invoice->id]) }}">
                                        @csrf
                                        @method('put')
                                        <button id="restore-{{ $invoice->id }}">restore</button>
                                    </form>
                                @endcan

                                @can('force delete invoice')
                                    <a class="dropdown-item delete" href="#" data-id="{{ $invoice->id }}"
                                        data-number="{{ $invoice->number }}" data-toggle="modal"
                                        data-target="#delete_invoice"><i class="text-danger fas fa-trash-alt"></i> حذف
                                        نهائي</a>
                                    <form class="d-none" method="POST"
                                        action="{{ route('invoices.forceDestroy', $invoice->id) }}">
                                        @csrf
                                        @method('delete')
                                        <button id="delete-{{ $invoice->id }}">delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                    </td>
                </tr>
            @empty
                <p class="text-center py-2 font-weight-bold">
                    الارشيف فارغ
                </p>
            @endforelse
        </x-slot:tableBody>
        <x-slot:tableBottom>

            <div class="p-2 mr-5">
                {{ $invoices->withQueryString()->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script>
            const restoreElements = document.querySelectorAll('.restore');

            function restoreEvent(e) {
                const invoiceId = e.target.getAttribute('data-id');
                const invoiceNumber = e.target.getAttribute('data-number');
                Swal.fire({
                    title: `هل انت متأكد من انك تريد استعادة الفاتورة ${invoiceNumber}`,
                    showCancelButton: true,
                    confirmButtonText: 'استعادة',
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`restore-${invoiceId}`).click();
                    }
                });
            }


            restoreElements.forEach((item) => {
                item.addEventListener('click', restoreEvent);
            });

            const deleteElements = document.querySelectorAll('.delete');

            function deleteEvent(e) {
                const invoiceId = e.target.getAttribute('data-id');
                const invoiceNumber = e.target.getAttribute('data-number');
                Swal.fire({
                    title: `هل انت متأكد من انك تريد حذف الفاتورة ${invoiceNumber}`,
                    showCancelButton: true,
                    confirmButtonText: 'حذف',
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-${invoiceId}`).click();
                    }
                });
            }


            deleteElements.forEach((item) => {
                item.addEventListener('click', deleteEvent);
            });
        </script>
    @endpush
</x-layouts.app>
