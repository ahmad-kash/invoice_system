<x-layouts.app>
    <x-slot:title>تعديل الفاتورة</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">الفوتير</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>
    <div class="card card-primary mx-3 my-2">
        <!-- /.card-header -->
        <!-- form start -->
        <form id="edit-form" class="p-3" method="POST" action="{{ route('invoices.update', $invoice->id) }}">
            @csrf
            @method('put')
            <div class="row">
                <div class="col">
                    <x-input-label for="number">رقم الفاتورة</x-input-label>
                    <x-text-input id="number" name="number" value="{{ old('number') ?? $invoice->number }}"
                        type="text" title="يرجي ادخال رقم الفاتورة" required autofocus :hasError="$errors->has('number')" />
                    <x-input-error :message="$errors->first('number')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="payment_date">تاريخ الفاتورة</x-input-label>
                    <x-text-input id="payment_date" name="payment_date"
                        value="{{ old('payment_date') ?? $invoice->payment_date }}" class="fc-datepicker"
                        placeholder="YYYY-MM-DD" type="text" value="{{ date('Y-m-d') }}" required
                        :hasError="$errors->has('payment_date')" />
                    <x-input-error :message="$errors->first('payment_date')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="due_date">تاريخ الاستحقاق</x-input-label>
                    <x-text-input id="due_date" name="due_date" value="{{ old('due_date') ?? $invoice->due_date }}"
                        class="fc-datepicker" placeholder="YYYY-MM-DD" type="text" required :hasError="$errors->has('due_date')" />
                    <x-input-error :message="$errors->first('due_date')"></x-input-error>
                </div>

            </div>

            {{-- 2 --}}
            <div class="row">
                <div class="col">
                    <label for="section_id" class="control-label">القسم</label>
                    <select id="section_id" name="section_id" class="form-control" required>
                        @foreach ($sections as $section)
                            <option @selected(old('section') == $section->id || $invoice->section->id == $section->id) value="{{ $section->id }}"> {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <label for="product_id" class="control-label">المنتج</label>
                    <select id="product_id" name="product_id" class="form-control" required>
                        <option value="{{ $invoice->product->id }}"> {{ $invoice->productName }}</option>
                    </select>
                </div>

                <div class="col">
                    <x-input-label for="collection_amount">مبلغ التحصيل</x-input-label>
                    <x-text-input type="text" id="collection_amount" name="collection_amount"
                        value="{{ old('collection_amount') ?? $invoice->collection_amount }}" oninput="asFloat(this)"
                        :hasError="$errors->has('collection_amount')" required />
                    <x-input-error :message="$errors->first('collection_amount')"></x-input-error>
                </div>
            </div>


            {{-- 3 --}}

            <div class="row">

                <div class="col">
                    <x-input-label for="commission_amount">مبلغ العمولة</x-input-label>
                    <x-text-input type="text" id="commission_amount" name="commission_amount"
                        value="{{ old('commission_amount') ?? $invoice->commission_amount }}"
                        title="يرجي ادخال مبلغ العمولة " oninput="asFloat(this)" required :hasError="$errors->has('commission_amount')" />
                    <x-input-error :message="$errors->first('commission_amount')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="discount">الخصم</x-input-label>
                    <x-text-input type="text" id="discount" name="discount"
                        value="{{ old('discount') ?? $invoice->discount }}" title="يرجي ادخال مبلغ الخصم " required
                        :hasError="$errors->has('discount')" />
                    <x-input-error :message="$errors->first('discount')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="VAT_rate">نسبة ضريبة القيمة المضافة</x-input-label>
                    <x-text-input name="VAT_rate" id="VAT_rate" onchange="myFunction()"
                        value="{{ old('VAT_rate') ?? '%' . $invoice->VAT_rate }}" oninput="asPercentage(this)"
                        required />
                    <x-input-error :message="$errors->first('VAT_rate')"></x-input-error>
                </div>

            </div>

            {{-- 4 --}}

            <div class="row">
                <div class="col">
                    <x-input-label for="VAT_value">قيمة ضريبة القيمة المضافة</x-input-label>
                    <x-text-input type="text" id="VAT_value" name="VAT_value" readonly
                        value="{{ old('VAT_value') ?? $invoice->VAT_value }}" :hasError="$errors->has('VAT_value')" />
                    <x-input-error :message="$errors->first('VAT_value')"></x-input-error>
                </div>

                <div class="col">
                    <x-input-label for="total">الاجمالي شامل الضريبة</x-input-label>
                    <x-text-input type="text" id="total" name="total" readonly
                        value="{{ old('total') ?? $invoice->total }}" :hasError="$errors->has('total')" />
                    <x-input-error :message="$errors->first('total')"></x-input-error>
                </div>
            </div>

            {{-- 5 --}}
            <div class="row">
                <div class="col">
                    <x-input-label for="note">ملاحظات</x-input-label>
                    <x-textarea id="note" name="note" rows="3"
                        :hasError="$errors->has('note')">{{ old('note') ?? $invoice->note }}</x-textarea>
                    <x-input-error :message="$errors->first('note')"></x-input-error>
                </div>
            </div><br>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </form>
    </div>
    @push('bodyScripts')
        <script type="module">
            import InvoiceForm from "{{ URL::asset('dist/js/forms/invoiceform/InvoiceForm.js') }}";
            const form = new InvoiceForm('#edit-form')
        </script>
    @endpush
</x-layouts.app>
