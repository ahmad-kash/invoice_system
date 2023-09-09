<x-layouts.app>

    <x-slot:title>دفع الفاتورة</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                <a href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">الفاتورة
                    {{ $invoice->number }}
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">الفواتير</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>
    </x-slot:breadcrumb>
    <div class="card  mx-3 my-2 p-3">
        <div class="card-header">
            <div class="row justify-content-center">
                <h2 class="card-title font-weight-bold">معلومات الفاتورة</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <x-input-label for="number">رقم الفاتورة</x-input-label>
                <x-text-input readonly disabled id="number" name="number" value="{{ $invoice->number }}"
                    type="text" />
            </div>

            <div class="col">
                <x-input-label for="create_date">تاريخ الفاتورة</x-input-label>
                <x-text-input readonly disabled id="create_date" name="create_date" value="{{ $invoice->create_date }}"
                    class="fc-datepicker" placeholder="YYYY-MM-DD" type="text" value="{{ date('Y-m-d') }}" />
            </div>

            <div class="col">
                <x-input-label for="due_date">تاريخ الاستحقاق</x-input-label>
                <x-text-input readonly disabled id="due_date" name="due_date" value="{{ $invoice->due_date }}"
                    class="fc-datepicker" placeholder="YYYY-MM-DD" type="text" />
            </div>

        </div>

        {{-- 2 --}}
        <div class="row">
            <div class="col">
                <label for="section" class="control-label">القسم</label>
                <x-text-input readonly disabled id="section" name="section" value="{{ $invoice->sectionName }}"
                    type="text" />
            </div>

            <div class="col">
                <label for="prodcut" class="control-label">المنتج</label>
                <x-text-input readonly disabled id="product" name="product" value="{{ $invoice->productName }}"
                    type="text" />
            </div>

            <div class="col">
                <x-input-label for="collection_amount">مبلغ التحصيل</x-input-label>
                <x-text-input readonly disabled type="text" id="collection_amount" name="collection_amount"
                    value="{{ $invoice->collection_amount }}" />
            </div>
        </div>


        {{-- 3 --}}

        <div class="row">

            <div class="col">
                <x-input-label for="commission_amount">مبلغ العمولة</x-input-label>
                <x-text-input readonly disabled type="text" id="commission_amount" name="commission_amount"
                    value="{{ $invoice->commission_amount }}" />
            </div>

            <div class="col">
                <x-input-label for="discount">الخصم</x-input-label>
                <x-text-input readonly disabled type="text" id="discount" name="discount"
                    value="{{ $invoice->discount }}" />
            </div>

            <div class="col">
                <x-input-label for="VAT_rate">نسبة ضريبة القيمة المضافة</x-input-label>
                <x-text-input readonly disabled name="VAT_rate" id="VAT_rate"
                    value="{{ '%' . $invoice->VAT_rate }}" />
            </div>

        </div>

        {{-- 4 --}}

        <div class="row">
            <div class="col">
                <x-input-label for="VAT_value">قيمة ضريبة القيمة المضافة</x-input-label>
                <x-text-input disabled type="text" id="VAT_value" name="VAT_value" readonly
                    value="{{ $invoice->VAT_value }}" />
            </div>

            <div class="col">
                <x-input-label for="total">الاجمالي شامل الضريبة</x-input-label>
                <x-text-input disabled type="text" id="total" name="total" readonly
                    value="{{ $invoice->total }}" />
            </div>

            <div class="col">
                <x-input-label for="total">القيمة المدفوعة مسبقا</x-input-label>
                <x-text-input disabled type="text" id="total" name="total" readonly
                    value="{{ $paymentsSum }}" />
            </div>
        </div>

        {{-- 5 --}}
        <div class="row">
            <div class="col">
                <x-input-label for="note">ملاحظات</x-input-label>
                <x-textarea id="note" name="note" rows="3" value="{{ $invoice->note }}"
                    readonly></x-textarea>
            </div>
        </div><br>
        <form id="edit-form" method="POST" action="{{ route('invoices.payments.store', $invoice->id) }}">
            @csrf
            <div class="form-group">
                <x-input-label for="amount">قيمة الدفع</x-input-label>
                <x-text-input type="text" id="amount" name="amount" value="{{ old('amount') ?? '' }}"
                    :hasError="$errors->has('amount')" />
                <x-input-error :message="$errors->first('amount')"></x-input-error>
            </div>

            <div class="form-group">
                <x-input-label for="note">ملاحظات</x-input-label>
                <x-textarea id="note" name="note" rows="3" value="{{ old('note') ?? '' }}"
                    :hasError="$errors->has('note')"></x-textarea>
                <x-input-error :message="$errors->first('note')"></x-input-error>

            </div><br>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </form>

    </div>
    </div>
</x-layouts.app>
