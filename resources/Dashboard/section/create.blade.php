<x-layouts.app>

    <x-slot:title>أضافة قسم جديد</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">الأقسام</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>
    <div class="card card-primary mx-3 my-2">
        <!-- /.card-header -->
        <!-- form start -->
        <form class="p-3" method="POST" action="{{ route('sections.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <x-input-label for="name">الاسم</x-input-label>
                    <x-text-input id="name" type="name" name="name" value="{{ old('name') }}" required
                        autocomplete="name" autofocus :hasError="$errors->has('name')" />
                    <x-input-error :message="$errors->first('name')"></x-input-error>
                </div>
                <div class="form-group">
                    <x-input-label for="description">الوصف</x-input-label>
                    <x-textarea id="description" name="description" autocomplete="description" rows="5"
                        :hasError="$errors->has('description')">{{ old('description') }}</x-textarea>
                    <x-input-error :message="$errors->first('description')"></x-input-error>
                </div>
                <!-- /.card-body -->
            </div>
            <div>
                <button type="submit" class="btn btn-primary mr-3">حفظ</button>
            </div>
        </form>
    </div>
</x-layouts.app>
