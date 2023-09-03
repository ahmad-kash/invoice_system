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
            <a class="btn btn-primary" href="{{ route('invoices.create') }}">اضافة فاتورة</a>
        </x-slot:tableTop>
        <x-slot:tableHeader>
            <th>#</th>
            <th>رقم الفاتورة</th>
            <th>تاريخ القاتورة</th>
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
                    <td>{{ $invoice->number }} </td>
                    <td>{{ $invoice->create_date }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>{{ $invoice->product }}</td>
                    <td><a href="#">
                            {{-- href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}"> --}}{{ $invoice->section->section_name }}
                        </a>
                    </td>
                    <td>{{ $invoice->discount }}</td>
                    <td>{{ $invoice->VAT_value }}</td>
                    <td>{{ $invoice->VAT_value }}</td>
                    <td>{{ $invoice->total }}</td>
                    <td>
                        {{-- @if ($invoice->Value_Status == 1)
                            <span class="text-success">{{ $invoice->Status }}</span>
                        @elseif($invoice->Value_Status == 2)
                            <span class="text-danger">{{ $invoice->Status }}</span>
                        @else
                            <span class="text-warning">{{ $invoice->Status }}</span>
                        @endif --}}
                        {{ $invoice->state }}
                    </td>

                    <td>{{ $invoice->note }}</td>
                    <td>
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary btn-sm"
                                data-toggle="dropdown" type="button">العمليات<i
                                    class="fas fa-caret-down ml-1"></i></button>
                            <div class="dropdown-menu tx-13">
                                @can('edit invoice')
                                    <a class="dropdown-item" href=" route('invoices.edit',['invoice'=>$invoice->id])">تعديل
                                        الفاتورة</a>
                                @endcan

                                @can('delete invoice')
                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                        data-toggle="modal" data-target="#delete_invoice"><i
                                            class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                        الفاتورة</a>
                                @endcan
                                {{--
                                @can('change invoice state')
                                    <a class="dropdown-item" href="{{ URL::route('Status_show', [$invoice->id]) }}"><i
                                            class=" text-success fas
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    fa-money-bill"></i>&nbsp;&nbsp;تغير
                                        حالة
                                        الدفع</a>
                                @endcan

                                @can('archive invoice')
                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                        data-toggle="modal" data-target="#Transfer_invoice"><i
                                            class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                        الارشيف</a>
                                @endcan

                                @can('print invoice')
                                    <a class="dropdown-item" href="Print_invoice/{{ $invoice->id }}"><i
                                            class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                        الفاتورة
                                    </a>
                                @endcan --}}
                            </div>
                        </div>

                    </td>
                </tr>
            @empty
                <p class="text-center py-2 font-weight-bold">
                    لا يوجد فواتير
                </p>
            @endforelse
        </x-slot:tableBody>
        <x-slot:tableBottom>

            <div class="p-2 mr-5">
                {{ $invoices->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            const elements = document.querySelectorAll('.btn-danger');
            console.log("{{ route('invoices.index') }}");

            function deleteEvent(e) {
                const invoiceId = e.target.getAttribute('data-id');
                const invoiceName = e.target.getAttribute('data-name');
                Swal.fire({
                    title: `هل انت متأكد من انك تريد حذف الفاتورة ${invoiceName}`,
                    showCancelButton: true,
                    confirmButtonText: 'حذف',
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-${invoiceId}`).click();
                    }
                });
            }


            elements.forEach((item) => {
                item.addEventListener('click', deleteEvent);
            });
        </script>
    @endpush
</x-layouts.app>
