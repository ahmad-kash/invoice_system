<x-layouts.app>

    <x-slot:title>اضافة مستخدم جديد</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">المستخدمين</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>خطا</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">رجوع</a>
                        </div>
                    </div><br>
                    <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                        action="{{ route('users.store', 'test') }}" method="post">
                        @csrf

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6" id="fnWrapper">
                                <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20" autofocus
                                    data-parsley-class-handler="#lnWrapper" name="name" required
                                    value="{{ old('name') ?? '' }}" type="text">
                            </div>

                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="email" required
                                    value="{{ old('email') ?? '' }}" type="email">
                            </div>
                        </div>

                        <div class="row row-sm mg-b-20 align-items-center">
                            <div class="col-xs-6 col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label"> صلاحية المستخدم</label>
                                    <select id="role" class="form-control multiple" name="role">
                                        <option selected disabled>حدد وظيفة المستخدم</option>
                                        @foreach ($roles as $role)
                                            <option @selected(old('role') === $role->name) value="{{ $role->name }}">
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group ml-5 mt-4">
                                    <input name="is_active" type="checkbox" class="form-check-input" id="is_active"
                                        value="1">
                                    <label class="form-check-label" for="is_active">مفعل</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mg-b-20">

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-5 text-center">
                            <button class="btn btn-primary px-5" type="submit">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
