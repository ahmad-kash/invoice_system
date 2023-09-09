<x-layouts.app>

    <x-slot:title>الأقسام</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">الأقسام</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableTop>
            <a class="btn btn-primary" href="{{ route('sections.create') }}">اضافة قسم</a>
        </x-slot:tableTop>
        <x-slot:tableHeader>
            <th style="width: 10px">#</th>
            <th>الاسم</th>
            <th>الوصف</th>
            <th></th>
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @forelse ($sections as $section)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $section->name }}</td>
                    <td>
                        <div class="d-block text-truncate">{{ $section->description }}</div>
                    </td>
                    <td>

                        <div class="felx">
                            @can('edit section')
                                <a href="{{ route('sections.edit', ['section' => $section->id]) }}"
                                    class="btn btn-secondary text-white">تعديل</a>
                            @endcan
                            @can('delete section')
                                <button class="btn btn-danger text-white" data-name="{{ $section->name }}"
                                    data-id="{{ $section->id }}">حذف</button>
                                <form class="d-none" method="POST" action="{{ route('sections.destroy', $section->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button id="delete-{{ $section->id }}">delete</button>
                                </form>
                            @endcan

                        </div>
                    </td>
                </tr>
            @empty
                <p class="text-center py-2 font-weight-bold">
                    لا يوجد اقسام
                </p>
            @endforelse
        </x-slot:tableBody>
        <x-slot:tableBottom>

            <div class="p-2 mr-5">
                {{ $sections->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const elements = document.querySelectorAll('.btn-danger');

            function deleteEvent(e) {
                const sectionId = e.target.getAttribute('data-id');
                const sectionName = e.target.getAttribute('data-name');
                Swal.fire({
                    title: `هل انت متأكد من انك تريد حذف القسم ${sectionName}`,
                    showCancelButton: true,
                    confirmButtonText: 'حذف',
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-${sectionId}`).click();
                    }
                });
            }


            elements.forEach((item) => {
                item.addEventListener('click', deleteEvent);
            });
        </script>
    @endpush
</x-layouts.app>
