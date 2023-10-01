<?php

namespace App\Http\Controllers;

use App\DTO\InvoiceDTO;
use App\Enums\InvoiceState;
use App\Http\Requests\InvoiceFilterRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Section;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{

    public function __construct(private InvoiceService $invoiceService)
    {
        $this->authorizeResource(Invoice::class, 'invoice');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(InvoiceFilterRequest $request)
    {

        return view(
            'invoice.index',
            ['invoices' => $this->invoiceService->getAllWithFiltering($filters = $request->query())]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('invoice.create', ['sections' => Section::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = $this->invoiceService->store(
            InvoiceDTO::fromArray($request->validated()),
            $request->validated('files')
        );

        return redirect()->route('invoices.index')->with('successMessage', 'تم اضافة الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('paymentDetails.user', 'attachments.user');
        return view('invoice.show', ['invoice' => $invoice]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return view('invoice.edit', ['invoice' => $invoice, 'sections' => Section::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice = $this->invoiceService->update(
            InvoiceDTO::fromArray($request->validated() + $invoice->getAttributes()),
            $invoice
        );

        return redirect()->route('invoices.index')->with('successMessage', 'تم تعديل الفاتورة بنجاح');
    }

    public function destroy(Invoice $invoice)
    {
        if ($this->invoiceService->delete($invoice))
            return redirect()->route('invoices.index')->with('successMessage', 'تم ارشفة الفاتورة بنجاح');
        return back()->with('errorMessage', 'حصل مشكلة لم يتم ارشفة الفاتورة');
    }
}
