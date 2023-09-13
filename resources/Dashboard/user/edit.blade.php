<x-layouts.app>
    <x-slot:title>تعديل بيانات المستخدم</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">المستخدمين</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>
    <div class="card card-primary mx-3 my-2">
        <!-- /.card-header -->
        <!-- form start -->
        <form class="p-3" method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('put')
            <div class="card-body">
                <div class="row mg-b-20">
                    <div class="parsley-input col-md-6" id="fnWrapper">
                        <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                        <input class="form-control form-control-sm mg-b-20" autofocus
                            data-parsley-class-handler="#lnWrapper" name="name" required
                            value="{{ old('name') ?? $user->name }}" type="text">
                    </div>

                    <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                        <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                        <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper"
                            name="email" required value="{{ old('email') ?? $user->email }}" type="email">
                    </div>
                </div>
                <div class="row row-sm mg-b-20 align-items-center">
                    <div class="col-xs-6 col-md-6">
                        <div class="form-group">
                            <label for="role" class="form-label"> صلاحية المستخدم</label>
                            <select id="role" class="form-control multiple" name="role">
                                <option selected disabled>حدد وظيفة المستخدم</option>
                                @foreach ($roles as $role)
                                    <option @selected(old('role') === $role->name || $user->roleName === $role->name) value="{{ $role->name }}">
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group ml-5 mt-4">
                            <input name="is_active" type="checkbox" class="form-check-input" id="is_active"
                                @checked(old('is_active') ?? $user->is_active) value="1">
                            <label class="form-check-label" for="is_active">مفعل</label>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mr-3">حفظ</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
