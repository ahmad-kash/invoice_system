<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeInvoicePaymentRequest;
use App\Models\Invoice;
use App\Models\PaymentDetail;
use App\Services\Invoice\InvoicePaymentService;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvoicePaymentController extends Controller
{

    public function __construct(protected InvoicePaymentService $invoicePaymentService)
    {
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice)
    {
        Gate::authorize('make-a-payment');

        return view(
            'invoice.payment.create',
            [
                'invoice' => $invoice,
                'paymentsSum' => $this->invoicePaymentService->getSumOfAllPreviousPayments($invoice)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeInvoicePaymentRequest $request, Invoice $invoice)
    {
        Gate::authorize('make-a-payment');

        $this->invoicePaymentService->pay($invoice, $request->input('amount'), $request->input('note'));

        return redirect()->route('invoices.show', ['invoice' => $invoice->id]);
    }
}
