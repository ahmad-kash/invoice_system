<?php

namespace App\Http\Controllers;

use App\DTO\InvoiceDTO;
use App\Enums\InvoiceState;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Section;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class InvoiceController extends Controller
{

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
            'forceDestroy' => 'forceDelete',
            'restore' => 'restore',
        ];
    }
    public function __construct()
    {
        $this->authorizeResource(Invoice::class, 'invoice');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(QueryBuilder $builder)
    {
        return view('invoice.index', ['invoices' => Invoice::paginate(5)]);
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
        $invoice = InvoiceService::make(
            InvoiceDTO::fromArray($request->validated()),
            $request->validated('files') ?? []
        )->store();

        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
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
        $invoice = InvoiceService::make(
            InvoiceDTO::fromArray($request->validated() + $invoice->getAttributes())
        )->update($invoice);

        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function forceDestroy(Invoice $invoice)
    {
        $invoice->forceDelete();
        return redirect()->route('invoices.index');
    }

    public function restore(Invoice $invoice)
    {
        $invoice->restore();
        return redirect()->route('invoices.index');
    }
}
