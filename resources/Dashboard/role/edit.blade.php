<x-layouts.app>

    <x-slot:title>أضافة دور جديد</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">الأدوار</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>
    <div class="card card-primary mx-3 my-2">
        <!-- /.card-header -->
        <!-- form start -->
        <form class="p-3" method="POST" action="{{ route('roles.update', ['role' => $role->id]) }}">
            @csrf
            @method('put')
            <div class="card-body">
                <div class="form-group">
                    <x-input-label for="name">الاسم</x-input-label>
                    <x-text-input id="name" type="name" name="name" value="{{ old('name') ?? $role->name }}"
                        required autocomplete="name" autofocus :hasError="$errors->has('name')" />
                    <x-input-error :message="$errors->first('name')"></x-input-error>
                </div>
                <div class="form-group">
                    <ul>
                        @if ($errors->first('permissions'))
                            <span class="text-danger" style="font-size:80%">
                                <strong>{{ $errors->first('permissions') }}</strong>
                            </span>
                        @endif
                        @foreach ($permissions as $type => $permissionsForType)
                            <li>
                                <h4>اذونات {{ __($type) }}</h4>
                                <ul>
                                    @foreach ($permissionsForType as $permission)
                                        <li>
                                            <label style="font-size: 16px;" for="permission-{{ $permission->id }}">
                                                {{ __($permission->name) }}</label>
                                            @if (old('permissions'))
                                                <input id="permission-{{ $permission->id }}" name="permissions[]"
                                                    @checked(in_array($permission->name, old('permissions'))) type="checkbox"
                                                    value="{{ $permission->name }}">
                                            @else
                                                <input id="permission-{{ $permission->id }}" name="permissions[]"
                                                    @checked(in_array($permission->name, $role->permissions->pluck('name')->toArray())) type="checkbox"
                                                    value="{{ $permission->name }}">
                                            @endif
                                        </li>
                                    @endforeach

                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
            <div>
                <button type="submit" class="btn btn-primary mr-3">حفظ</button>
            </div>
        </form>
    </div>
</x-layouts.app>
