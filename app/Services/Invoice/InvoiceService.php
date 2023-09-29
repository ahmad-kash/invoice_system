<?php

namespace App\Services\Invoice;

use App\DTO\InvoiceDTO;
use App\Models\Invoice;
use App\Notifications\Database\Invoice\InvoiceCreated;
use App\Notifications\Database\Invoice\InvoiceDeleted;
use App\Notifications\Database\Invoice\InvoiceForceDeleted;
use App\Notifications\Database\Invoice\InvoiceRestored;
use App\Notifications\Database\Invoice\InvoiceUpdated;
use App\Services\Notification\AdminNotifyService;
use Illuminate\Support\Collection;
use SplFileInfo;

class InvoiceService
{

    public function __construct(
        private InvoiceAttachmentService $invoiceAttachment,
        private AdminNotifyService $adminNotifyService
    ) {
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

        $this->adminNotifyService->notifyAdmins(new InvoiceCreated($invoice, auth()->user()));

        return $invoice;
    }
    public function update(InvoiceDTO $newInvoiceDTOData, Invoice $invoice): Invoice
    {
        $invoiceDTO = InvoiceCalCulator::calculate($newInvoiceDTOData);

        $invoice->update($invoiceDTO->toArray());

        $this->adminNotifyService->notifyAdmins(new InvoiceUpdated($invoice, auth()->user()));

        return $invoice;
    }

    public function restore(Invoice $invoice): bool
    {
        $isRestore = $invoice->restore();
        if ($isRestore)
            $this->adminNotifyService->notifyAdmins(new InvoiceRestored($invoice, auth()->user()));

        return $isRestore;
    }
    public function delete(Invoice $invoice): bool
    {
        // this is a soft delete we will not delete any record but set deleted_at to true
        $isDeleted = $invoice->delete();
        if ($isDeleted)
            $this->adminNotifyService->notifyAdmins(new InvoiceDeleted($invoice, auth()->user()));
        return $isDeleted;
    }
    public function forceDelete(Invoice $invoice): bool
    {
        //here we should delete all the invoice attachments for this invoice
        if ($invoice->attachments()->exists())
            $this->invoiceAttachment->deleteAll($invoice);

        $isDeleted = $invoice->forceDelete();
        if ($isDeleted)
            $this->adminNotifyService->notifyAdmins(new InvoiceForceDeleted($invoice, auth()->user()));
        return $isDeleted;
    }
}
