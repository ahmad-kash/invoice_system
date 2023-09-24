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

    public function getAllWithFiltering(array $filters)
    {
        return Invoice::with(['product', 'section'])->filter($filters)->paginate(5);
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

    public function restore(Invoice $invoice): bool
    {
        return $invoice->restore();
    }
    public function delete(Invoice $invoice): bool
    {
        // this is a soft delete we will not delete any record but set deleted_at to true
        return $invoice->delete();
    }
    public function forceDelete(Invoice $invoice): bool
    {
        //here we should delete all the invoice attachments for this invoice
        $this->invoiceAttachment->deleteAll($invoice);

        return $invoice->forceDelete();
    }
}
