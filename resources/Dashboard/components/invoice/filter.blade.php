@props(['url'])
<div class="modal fade" id="filters" tabindex="-1" role="dialog" aria-labelledby="filtersLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtersLabel">فلترة الفواتير</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="search" action="{{ $url }}">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <x-input-label for="number">رقم الفاتورة</x-input-label>
                            <x-text-input id="number" name="number" type="text" value="{{ old('number') ?? '' }}"
                                title="يرجي ادخال رقم الفاتورة" autofocus />
                        </div>
                        <div class="row">
                            <x-input-label for="section">القسم</x-input-label>
                            <x-text-input id="section" name="section" type="text"
                                value="{{ old('section') ?? '' }}" title="يرجي ادخال اسم القسم" />
                        </div>
                        <div class="row">
                            <x-input-label for="product">المنتج</x-input-label>
                            <x-text-input id="product" name="product" type="text"
                                value="{{ old('product') ?? '' }}" title="يرجي ادخال اسم المنتج" />
                        </div>
                        <div class="row">
                            <x-input-label for="state">حالة الدفع</x-input-label>
                            <select id="state" name="state" class="form-control">
                                <option value="" selected>حدد حالة الدفع</option>
                                <option @selected(old('state') == 'paid') value="paid">مدفوعة</option>
                                <option @selected(old('state') == 'unPaid') value="unPaid">غير مدفوعة</option>
                                <option @selected(old('state') == 'partiallyPaid') value="partiallyPaid">مدفوعة جزئيا
                                </option>

                            </select>
                        </div>
                        <div>
                            <h4 class="mt-2">تاريخ دفع الفاتورة</h4>
                            <div class="row">
                                <div class="col">
                                    <x-input-label for="from">من</x-input-label>
                                    <x-text-input id="from" name="from" value="{{ old('from') ?? '' }}"
                                        :hasError="$errors->has('from')" class="fc-datepicker" type="text" placeholder="YYYY-MM-DD"
                                        autocomplete="off" />
                                    <x-input-error :message="$errors->first('from')"></x-input-error>
                                </div>
                                <div class="col">
                                    <x-input-label for="to">الى</x-input-label>
                                    <x-text-input id="to" name="to" value="{{ old('to') ?? '' }}"
                                        :hasError="$errors->has('to')" class="fc-datepicker" type="text" placeholder="YYYY-MM-DD"
                                        autocomplete="off" />
                                    <x-input-error :message="$errors->first('to')"></x-input-error>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('bodyScripts')
    <script>
        //remove name attribute from nullabel inputs on search
        let myForm = document.getElementById('search');

        myForm.addEventListener('submit', function() {
            let allInputs = myForm.querySelectorAll("input,select");
            let input = "";
            for (let i = 0; i < allInputs.length; i++) {
                input = allInputs[i];

                if (input.name && (!input.value || input.value == "")) {
                    input.name = '';
                }
            }
        });
    </script>
@endpush
