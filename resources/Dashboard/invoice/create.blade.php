<x-layouts.app>

    <x-slot:title>أضافة فاتورة جديدة</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">الفواتير</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>
    <div class="card card-primary mx-3 my-2">
        <form id="create-invoice" class="p-3" method="POST" action="{{ route('invoices.store') }}"
            enctype="multipart/form-data">
            @csrf
            {{-- 1 --}}

            <div class="row">
                <div class="col">
                    <x-input-label for="number">رقم الفاتورة</x-input-label>
                    <x-text-input id="number" name="number" type="text" value="{{ old('number') ?? '' }}"
                        title="يرجي ادخال رقم الفاتورة" required autofocus :hasError="$errors->has('number')" />
                    <x-input-error :message="$errors->first('number')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="payment_date">تاريخ الفاتورة</x-input-label>
                    <x-text-input id="payment_date" name="payment_date" value="{{ old('payment_date') ?? '' }}"
                        class="fc-datepicker" type="text" required :hasError="$errors->has('payment_date')" placeholder="YYYY-MM-DD" />
                    <x-input-error :message="$errors->first('payment_date')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="due_date">تاريخ الاستحقاق</x-input-label>
                    <x-text-input id="due_date" name="due_date" value="{{ old('due_date') ?? '' }}"
                        class="fc-datepicker" type="text" required :hasError="$errors->has('due_date')" placeholder="YYYY-MM-DD" />
                    <x-input-error :message="$errors->first('due_date')"></x-input-error>
                </div>

            </div>

            {{-- 2 --}}
            <div class="row">
                <div class="col">
                    <x-input-label for="section_id">القسم</x-input-label>
                    @if ($sections->isEmpty())
                        <div class="alert alert-danger">
                            رجاء قم باضافة قسم
                        </div>
                    @else
                        <select id="section_id" name="section_id" class="form-control" required>
                            <!--placeholder-->
                            <option value="" selected disabled>حدد القسم</option>
                            @foreach ($sections as $section)
                                <option @selected(old('section') == $section->id) value="{{ $section->id }}"> {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :message="$errors->first('section')"></x-input-error>
                    @endif
                </div>

                <div class="col">
                    <x-input-label for="product_id">المنتج</x-input-label>
                    <select id="product_id" name="product_id" class="form-control" required>
                        <option selected disabled>حدد المنتج المطلوب</option>
                    </select>
                    <x-input-error :message="$errors->first('product')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="collection_amount">مبلغ التحصيل</x-input-label>
                    <x-text-input type="text" id="collection_amount" name="collection_amount" required
                        value="{{ old('collection_amount') ?? '' }}" :hasError="$errors->has('collection_amount')" />
                    <x-input-error :message="$errors->first('collection_amount')"></x-input-error>
                </div>
            </div>


            {{-- 3 --}}

            <div class="row">

                <div class="col">
                    <x-input-label for="commission_amount">مبلغ العمولة</x-input-label>
                    <x-text-input type="text" id="commission_amount" name="commission_amount" required
                        value="{{ old('commission_amount') ?? '' }}" title="يرجي ادخال مبلغ العمولة " required
                        :hasError="$errors->has('commission_amount')" />
                    <x-input-error :message="$errors->first('commission_amount')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="discount">الخصم</x-input-label>
                    <x-text-input type="text" id="discount" name="discount" required
                        value="{{ old('discount') ?? '' }}" title="يرجي ادخال مبلغ الخصم " :hasError="$errors->has('discount')" />
                    <x-input-error :message="$errors->first('discount')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="VAT_rate">نسبة ضريبة القيمة المضافة</x-input-label>
                    <x-text-input name="VAT_rate" id="VAT_rate" value="{{ old('VAT_rate') ?? '%0' }}" required />
                    <x-input-error :message="$errors->first('VAT_rate')"></x-input-error>
                </div>

            </div>

            {{-- 4 --}}

            <div class="row">
                <div class="col">
                    <x-input-label for="VAT_value">قيمة ضريبة القيمة المضافة</x-input-label>
                    <x-text-input type="text" class="form-control" id="VAT_value" name="VAT_value" readonly
                        value="{{ old('VAT_value') ?? '' }}" :hasError="$errors->has('VAT_value')" />
                    <x-input-error :message="$errors->first('VAT_value')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="total">الاجمالي شامل الضريبة</x-input-label>
                    <x-text-input type="text" class="form-control" id="total" name="total" readonly
                        value="{{ old('total') ?? '' }}" :hasError="$errors->has('total')" />
                    <x-input-error :message="$errors->first('total')"></x-input-error>
                </div>
            </div>

            {{-- 5 --}}
            <div class="row">
                <div class="col">
                    <x-input-label for="note">ملاحظات</x-input-label>
                    <x-textarea class="form-control" id="note" name="note" rows="3"
                        value="{{ old('note') ?? '' }}" :hasError="$errors->has('note')"></x-textarea>
                    <x-input-error :message="$errors->first('note')"></x-input-error>
                </div>
            </div><br>

            <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
            <h5 class="card-title">المرفقات</h5>

            <div class="col-sm-12 col-md-12">
                <x-text-input accept=".pdf,.jpg, .png, image/jpeg, image/png" type="file" name="files[]" multiple
                    class="form-control dropify" :hasError="$errors->has('files.*')" data-height="7" />
                @if ($errors->has('files.*'))
                    <ul>
                        @foreach ($errors->get('files.*') as $errors)
                            @foreach ($errors as $error)
                                <li>
                                    <span class="text-danger">{{ $error }}</span>
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                @endif
            </div><br>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </form>
    </div>
    @push('bodyScripts')
        <script type="module">
            import InvoiceForm from "{{ URL::asset('dist/js/forms/invoiceform/InvoiceForm.js') }}";
            const form = new InvoiceForm('#create-invoice')
        </script>
    @endpush
</x-layouts.app>
