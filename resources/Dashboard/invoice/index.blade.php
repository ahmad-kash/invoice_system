<x-layouts.app>

    <x-slot:title>الفواتير</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">الفواتير</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableTop>
            <div class="flex">
                @can('create invoice')
                    <a class="btn btn-primary" href="{{ route('invoices.create') }}">اضافة فاتورة</a>
                @endcan
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filters">بحث</button>
            </div>
            <x-invoice.filter :url="route('invoices.index')"></x-invoice.filter>
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
            @canany(['edit invoice', 'delete invoice', 'force delete invoice', 'make-a-payment'])
                <th>العمليات</th>
            @endcan
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">
                            {{ $invoice->number }}
                        </a>
                    </td>
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
                    @canany(['edit invoice', 'delete invoice', 'force delete invoice', 'make-a-payment'])
                        <td>
                            <div class="dropdown">
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                                    type="button">العمليات</button>
                                <div class="dropdown-menu tx-13">
                                    @can('edit invoice')
                                        <a class="dropdown-item"
                                            href="{{ route('invoices.edit', ['invoice' => $invoice->id]) }} ">
                                            <i class="fas fa-edit"></i> تعديل
                                            الفاتورة</a>
                                    @endcan

                                    @can('delete invoice')
                                        <a class="dropdown-item archive" href="#" data-id="{{ $invoice->id }}"
                                            data-number="{{ $invoice->number }}" data-toggle="modal"
                                            data-target="#archive_invoice"><i class="text-warning fas fa-exchange-alt"></i>
                                            نقل
                                            الى
                                            الارشيف</a>
                                        <form class="d-none" method="POST"
                                            action="{{ route('invoices.destroy', $invoice->id) }}">
                                            @csrf
                                            @method('delete')
                                            <button id="archive-{{ $invoice->id }}">delete</button>
                                        </form>
                                    @endcan

                                    @can('make-a-payment')
                                        <a class="dropdown-item"
                                            href="{{ route('invoices.payments.create', ['invoice' => $invoice->id]) }}">
                                            <i class=" text-success fas fa-money-bill"></i>
                                            دفع الفاتورة</a>
                                    @endcan
                                    @can('force delete invoice')
                                        <a class="dropdown-item delete" href="#" data-id="{{ $invoice->id }}"
                                            data-number="{{ $invoice->number }}" data-toggle="modal"
                                            data-target="#delete_invoice"><i class="text-danger fas fa-trash-alt"></i> حذف</a>
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
                    @endcan
                </tr>
            @empty
                <p class="text-center py-2 font-weight-bold">
                    لا يوجد فواتير
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
            const archiveElements = document.querySelectorAll('.archive');

            function archiveEvent(e) {
                const invoiceId = e.target.getAttribute('data-id');
                const invoiceNumber = e.target.getAttribute('data-number');
                Swal.fire({
                    title: `هل انت متأكد من انك تريد ارشفة الفاتورة ${invoiceNumber}`,
                    showCancelButton: true,
                    confirmButtonText: 'ارشفة',
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`archive-${invoiceId}`).click();
                    }
                });
            }


            archiveElements.forEach((item) => {
                item.addEventListener('click', archiveEvent);
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
