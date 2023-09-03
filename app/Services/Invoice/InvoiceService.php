<?php

namespace App\Services\Invoice;

use App\DTO\InvoiceDTO;
use App\Models\Invoice;
use App\Services\UploadFileService;

class InvoiceService
{
    private InvoiceFilesUploader $invoiceFilesUploader;

    private InvoiceDTO $invoiceDTO;

    public function __construct(InvoiceDTO $invoice, array $files = [])
    {
        $this->invoiceFilesUploader = new InvoiceFilesUploader($files);

        $this->invoiceDTO = $invoice;
    }
    public static function make(InvoiceDTO $invoiceData, array $files = [])
    {
        return new self($invoiceData, $files);
    }

    public function store(): Invoice
    {
        $this->invoiceDTO = InvoiceCalCulator::make($this->invoiceDTO)->execute();
        $invoice = Invoice::create($this->invoiceDTO->toArray());

        $this->invoiceFilesUploader->execute($invoice);

        return $invoice;
    }
    public function update(Invoice $invoice): Invoice
    {
        $this->invoiceDTO = InvoiceCalCulator::make($this->invoiceDTO)->execute();

        $invoice->update($this->invoiceDTO->toArray());

        return $invoice;
    }
}
