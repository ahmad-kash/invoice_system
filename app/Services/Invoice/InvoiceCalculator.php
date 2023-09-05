<?php

namespace App\Services\Invoice;

use App\DTO\InvoiceDTO;
use Illuminate\Support\Str;

class InvoiceCalculator
{
    public static function calculate(InvoiceDTO $invoice)
    {
        $invoiceArray = $invoice->toArray();

        // calculate VAT_value and total
        $commissionAmountAfterDiscount = $invoice->commission_amount - $invoice->discount;
        $VATValue = $commissionAmountAfterDiscount * $invoice->VAT_rate / 100;
        $total = $VATValue + $commissionAmountAfterDiscount;

        $invoiceArray['VAT_value'] = $VATValue;
        $invoiceArray['total'] = $total;

        return InvoiceDTO::fromArray($invoiceArray);
    }
}
