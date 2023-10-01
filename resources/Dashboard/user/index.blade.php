<x-layouts.app>

    <x-slot:title>قائمة المستخدمين</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">المستخدمين</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableTop>
            @can('create user')
                <a class="btn btn-primary" href="{{ route('users.create') }}">اضافة مستخدم</a>
            @endcan
        </x-slot:tableTop>
        <x-slot:tableHeader>
            <th class="wd-10p border-bottom-0">#</th>
            <th class="wd-15p border-bottom-0">اسم المستخدم</th>
            <th class="wd-20p border-bottom-0">البريد الالكتروني</th>
            <th class="wd-15p border-bottom-0">حالة المستخدم</th>
            <th class="wd-15p border-bottom-0">نوع المستخدم</th>
            @canany(['edit user', 'delete user', 'force delete user', 'reset password'])
                <th class="wd-10p border-bottom-0">العمليات</th>
            @endcan
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <x-user.active :active="$user->is_active" />
                    </td>
                    <td>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $role)
                                <label class="badge badge-success">{{ $role }}</label>
                            @endforeach
                        @endif
                    </td>
                    @canany(['edit user', 'delete user', 'force delete user', 'reset password'])
                        <td>
                            <div class="dropdown">
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                                    type="button">العمليات</button>
                                <div class="dropdown-menu tx-13">
                                    @can('edit user')
                                        <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item" title="تعديل"><i
                                                class="fas fa-edit"></i> تعديل</a>
                                    @endcan

                                    @can('delete user')
                                        <a class="dropdown-item delete" data-effect="effect-scale" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}" data-toggle="modal" href="#" title="حذف"><i
                                                class="text-warning fas fa-trash-alt"></i> حذف</a>
                                        <form class="d-none" method="POST" action="{{ route('users.destroy', $user->id) }}">
                                            @csrf
                                            @method('delete')
                                            <button id="delete-{{ $user->id }}">delete</button>
                                        </form>
                                    @endcan
                                    @can('force delete user')
                                        <a class="dropdown-item force-delete" data-effect="effect-scale"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-toggle="modal"
                                            href="#" title="حذف نهائي"><i class="text-danger fas fa-trash-alt"></i> حذف
                                            نهائي</a>
                                        <form class="d-none" method="POST"
                                            action="{{ route('users.forceDestroy', $user->id) }}">
                                            @csrf
                                            @method('delete')
                                            <button id="force-delete-{{ $user->id }}">delete</button>
                                        </form>
                                    @endcan
                                    @can('reset password')
                                        <a class="dropdown-item reset" data-effect="effect-scale" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}" data-toggle="modal" href="#"
                                            title="اعادة تعيين كلمة المرور"><i class="text-warning fas fa-key"></i> إعادة
                                            تعيين كلمة المرور</a>
                                        <form class="d-none" method="POST"
                                            action="{{ route('password.reset.update', $user->id) }}">
                                            @csrf
                                            @method('put')
                                            <button id="reset-{{ $user->id }}">reset</button>
                                        </form>
                                    @endcan

                                </div>
                            </div>
                        </td>
                    @endcan
                </tr>
            @endforeach
        </x-slot:tableBody>
        <x-slot:tableBottom>

            <div class="p-2 mr-5">
                {{ $users->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script>
            const deleteElements = document.querySelectorAll('.delete');
            const forceDeleteElements = document.querySelectorAll('.force-delete');
            const resetElements = document.querySelectorAll('.reset');

            function clickEvent(e, title, confirmButtonText, callbackButtonPrefix) {
                const userId = e.target.getAttribute('data-id');
                const userName = e.target.getAttribute('data-name');
                Swal.fire({
                    title: `${title}${userName}`,
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(`${callbackButtonPrefix}-${userId}`);
                        document.getElementById(`${callbackButtonPrefix}-${userId}`).click();
                    }
                });
            }

            deleteElements.forEach((item) => {
                item.addEventListener('click', (e) => {
                    clickEvent(e,
                        'هل انت متأكد من انك تريد حذف المستخدم  ',
                        'حذف',
                        'delete'
                    )
                });
            });

            forceDeleteElements.forEach((item) => {
                item.addEventListener('click', (e) => {
                    clickEvent(e,
                        'سيتم حذف جميع البيانات المرتبطة به هل انت متأكد من انك تريد حذف المستخدم   ',
                        'حذف',
                        'force-delete'
                    )
                });
            });

            resetElements.forEach((item) => {
                item.addEventListener('click', (e) => {
                    clickEvent(e,
                        'هل انت متأكد من انك اعادة تعيين كلمة المرور للمستخدم  ',
                        'أعادة تعيين',
                        'reset'
                    )
                });
            });
        </script>
    @endpush
</x-layouts.app>
