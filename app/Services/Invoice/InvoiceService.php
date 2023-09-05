<?php

namespace App\Services\Invoice;

use App\DTO\InvoiceDTO;
use App\Models\Invoice;
use Illuminate\Support\Collection;
use SplFileInfo;

class InvoiceService
{

    public function __construct(private InvoiceAttachmentService $invoiceAttachment)
    {
    }

    public function store(InvoiceDTO $invoiceDTO, Null|array|Collection|SplFileInfo $files = null): Invoice
    {
        $invoiceDTO = InvoiceCalCulator::calculate($invoiceDTO);

        $invoice = Invoice::create($invoiceDTO->toArray());

        $this->invoiceAttachment->store($invoice, $files);

        return $invoice;
    }
    public function update(InvoiceDTO $newInvoiceDTOData, Invoice $invoice): Invoice
    {
        $invoiceDTO = InvoiceCalCulator::calculate($newInvoiceDTOData);

        $invoice->update($invoiceDTO->toArray());

        return $invoice;
    }
}
