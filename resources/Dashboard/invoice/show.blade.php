<x-layouts.app>

    <x-slot:title>الفاتورة</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">الفواتير</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>
    </x-slot:breadcrumb>

    <div class="card card-primary card-outline">
        <div class="card-body">
            <ul class="nav nav-tabs" id="content-tab" role="tablist">
                <x-invoice.show.tab-nav watch="invoice" :active="true">معلومات الفاتورة</x-invoice.show.tab-nav>
                <x-invoice.show.tab-nav watch="payment-history">تاريخ الدفع</x-invoice.show.tab-nav>
                <x-invoice.show.tab-nav watch="attachment">المرفقات</x-invoice.show.tab-nav>
            </ul>
            <div class="tab-content" id="content-tabContent">
                <x-invoice.show.tab-content id="invoice" :show="true">
                    <x-invoice.show.data :invoice="$invoice" />
                </x-invoice.show.tab-content>
                <x-invoice.show.tab-content id="payment-history">
                    <x-invoice.show.payment-history :paymentDetails="$invoice->paymentDetails" />
                </x-invoice.show.tab-content>
                <x-invoice.show.tab-content id="attachment">
                    <x-invoice.show.attachment :attachments="$invoice->attachments" :invoice="$invoice" />
                </x-invoice.show.tab-content>
            </div>

        </div>
    </div>
</x-layouts.app>
