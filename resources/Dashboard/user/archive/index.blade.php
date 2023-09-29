<x-layouts.app>

    <x-slot:title>ارشيف المستخدمين</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">ارشيف المستخدمين</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableHeader>
            <th class="wd-10p border-bottom-0">#</th>
            <th class="wd-15p border-bottom-0">اسم المستخدم</th>
            <th class="wd-20p border-bottom-0">البريد الالكتروني</th>
            <th class="wd-15p border-bottom-0">حالة المستخدم</th>
            <th class="wd-15p border-bottom-0">نوع المستخدم</th>
            <th class="wd-10p border-bottom-0">العمليات</th>
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @forelse ($users as $key => $user)
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

                    <td>
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true"
                                class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                                type="button">العمليات</button>
                            <div class="dropdown-menu tx-13">

                                @can('restore user')
                                    <a class="dropdown-item restore" data-effect="effect-scale"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-toggle="modal"
                                        href="#" title="حذف"><i class="text-warning fas fa-trash-restore"></i>
                                        استعادة</a>
                                    <form class="d-none" method="POST" action="{{ route('users.restore', $user->id) }}">
                                        @csrf
                                        @method('put')
                                        <button id="restore-{{ $user->id }}">restore</button>
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
                            </div>
                        </div>

                    </td>
                    <td>

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
                {{ $users->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const restoreElements = document.querySelectorAll('.restore');
            const forceDeleteElements = document.querySelectorAll('.force-delete');

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

            restoreElements.forEach((item) => {
                item.addEventListener('click', (e) => {
                    clickEvent(e,
                        'هل انت متأكد من انك تريد استعادة المستخدم  ',
                        'استعادة',
                        'restore'
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
        </script>
    @endpush
</x-layouts.app>
