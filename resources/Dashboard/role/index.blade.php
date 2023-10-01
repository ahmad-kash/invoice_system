<x-layouts.app>

    <x-slot:title>قائمة الادوار</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">الادوار</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableTop>
            <a class="btn btn-primary" href="{{ route('roles.create') }}">اضافة دور</a>
        </x-slot:tableTop>
        <x-slot:tableHeader>
            <th class="wd-10p border-bottom-0">#</th>
            <th class="wd-15p border-bottom-0">اسم الدور</th>
            <th class="wd-15p border-bottom-0">الاذونات</th>
            <th class="wd-10p border-bottom-0">العمليات</th>
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @if (!empty($role->permissions))
                            @foreach ($role->permissions->pluck('name') as $permission)
                                <label class="badge badge-success">{{ $permission }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true"
                                class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                                type="button">العمليات</button>
                            <div class="dropdown-menu tx-13">
                                @can('edit role')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="dropdown-item" title="تعديل"><i
                                            class="fas fa-edit"></i> تعديل</a>
                                @endcan
                                @can('delete role')
                                    <a class="dropdown-item delete" data-effect="effect-scale" data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}" data-toggle="modal" href="#" title="حذف"><i
                                            class="text-warning fas fa-trash-alt"></i> حذف</a>
                                    <form class="d-none" method="POST" action="{{ route('roles.destroy', $role->id) }}">
                                        @csrf
                                        @method('delete')
                                        <button id="delete-{{ $role->id }}">delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                    </td>
                    <td>

                    </td>
                </tr>
            @endforeach
        </x-slot:tableBody>
        <x-slot:tableBottom>

            <div class="p-2 mr-5">
                {{ $roles->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script>
            const deleteElements = document.querySelectorAll('.delete');

            function clickEvent(e, title, confirmButtonText, callbackButtonPrefix) {
                const roleId = e.target.getAttribute('data-id');
                const roleName = e.target.getAttribute('data-name');
                Swal.fire({
                    title: `${title}${roleName}`,
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(`${callbackButtonPrefix}-${roleId}`);
                        document.getElementById(`${callbackButtonPrefix}-${roleId}`).click();
                    }
                });
            }

            deleteElements.forEach((item) => {
                item.addEventListener('click', (e) => {
                    clickEvent(e,
                        'هل انت متأكد من انك تريد حذف الدور  ',
                        'حذف',
                        'delete'
                    )
                });
            });
        </script>
    @endpush
</x-layouts.app>
