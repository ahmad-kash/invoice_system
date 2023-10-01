<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceFilterRequest;
use App\Models\Invoice;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;

class InvoiceArchiveController extends Controller
{
    public function __construct(private InvoiceService $invoiceService)
    {
        $this->authorizeResource(Invoice::class, 'invoice');
    }

    public function index(InvoiceFilterRequest $request)
    {
        return view(
            'invoice.archive.index',
            [
                'invoices' => $this->invoiceService->getAllWithFiltering($request->query(), onlyTrashed: true)
            ]
        );
    }

    public function update(Invoice $invoice)
    {
        $this->invoiceService->restore($invoice);

        return redirect()->back()->with('successMessage', 'تم استرجاع الفاتورة');
    }

    public function destroy(Invoice $invoice)
    {
        $this->invoiceService->forceDelete($invoice);

        return redirect()->back()->with('successMessage', 'تم حذف الفاتورة نهائيا');
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'restore',
            'update' => 'restore',
            'destroy' => 'forceDelete',
        ];
    }
}
