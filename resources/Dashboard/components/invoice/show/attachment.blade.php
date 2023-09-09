<div class="card card-statistics">
    @can('create invoice')
        <div class="card-body">
            <form method="post" action="{{ route('invoices.attachments.store', ['invoice' => $invoice->id]) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="my-3">
                    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                    <x-text-input accept=".pdf,.jpg, .png, image/jpeg, image/png" type="file" name="file" required
                        class="form-control" :hasError="$errors->has('file')" />
                    <x-input-error :message="$errors->first('file')"></x-input-error>
                </div>
                <button type="submit" class="btn btn-primary btn-sm " name="uploadedFile">تاكيد</button>
            </form>
        </div>
    @endcan
    <br>

    <div class="table-responsive mt-15">
        <table class="table center-aligned-table mb-0 table table-hover" style="text-align:center">
            <thead>
                <tr class="text-dark">
                    <th scope="col">#</th>
                    <th scope="col">اسم الملف</th>
                    <th scope="col">قام بالاضافة</th>
                    <th scope="col">تاريخ الاضافة</th>
                    <th scope="col">العمليات</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($attachments as $attachment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attachment->name }}</td>
                        <td>{{ $attachment->userName }}</td>
                        <td>{{ $attachment->created_at }}</td>
                        <td colspan="2">
                            <a class="btn btn-outline-success btn-sm"
                                href="{{ route('invoices.attachments.show', ['attachment' => $attachment->id]) }}"
                                role="button"><i class="fas fa-eye"></i>&nbsp;
                                عرض</a>

                            <a class="btn btn-outline-info btn-sm"
                                href="{{ route('invoices.attachments.download', ['attachment' => $attachment->id]) }}"
                                role="button"><i class="fas fa-download"></i>&nbsp;
                                تحميل</a>

                            @can('delete invoice')
                                <button class="btn btn-outline-danger btn-sm delete" data-toggle="modal"
                                    data-id="{{ $attachment->id }}" data-name="{{ $attachment->name }}"
                                    data-target="#delete_file">حذف</button>
                                <form class="d-none" method="POST"
                                    action="{{ route('invoices.attachments.destroy', $attachment->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button id="delete-{{ $attachment->id }}">delete</button>
                                </form>
                            @endcan

                        </td>
                    </tr>
                @empty
                    <h4 class="font-weight-bold text-center">
                        لا يوجد مرفقات
                    </h4>
                @endforelse
            </tbody>
            </tbody>
        </table>

    </div>
</div>
@push('bodyScripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const deleteElements = document.querySelectorAll('.delete');

        function deleteEvent(e) {
            const attachmentId = e.target.getAttribute('data-id');
            const attachmentName = e.target.getAttribute('data-name');
            Swal.fire({
                title: `هل انت متأكد من انك تريد حذف الملف ${attachmentName}`,
                showCancelButton: true,
                confirmButtonText: 'حذف',
                cancelButtonText: 'الغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-${attachmentId}`).click();
                }
            });
        }


        deleteElements.forEach((item) => {
            item.addEventListener('click', deleteEvent);
        });
    </script>
@endpush
