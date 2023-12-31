<?php

namespace App\DTO;

use App\DTO\Interfaces\DTOInterface;
use App\Enums\InvoiceState;
use Carbon\Carbon;
use Illuminate\Support\Str;


readonly class InvoiceDTO implements DTOInterface
{
    public function __construct(
        public  string  $number,
        public  string  $due_date,
        public  string  $payment_date,
        public  int  $product_id,
        public  int  $section_id,
        public  float  $collection_amount,
        public  float  $commission_amount,
        public  float  $discount,
        public  float  $VAT_rate,
        public  InvoiceState $state,
        public  string  $note,
        public  float  $VAT_value = 0,
        public  float  $total = 0,


    ) {
    }

    public static function fromArray(array $invoiceData): self
    {
        return new self(
            number: $invoiceData['number'],
            due_date: new Carbon($invoiceData['due_date']),
            payment_date: new Carbon($invoiceData['payment_date']),
            product_id: $invoiceData['product_id'],
            section_id: $invoiceData['section_id'],
            collection_amount: $invoiceData['collection_amount'],
            commission_amount: $invoiceData['commission_amount'],
            discount: $invoiceData['discount'],
            VAT_rate: self::VATRateToFloat($invoiceData['VAT_rate']), // from %5 to 5
            VAT_value: $invoiceData['VAT_value'] ?? 0,
            total: $invoiceData['total'] ?? 0,
            state: self::ToInvoiceState($invoiceData['state'] ?? null),
            note: $invoiceData['note'] ?? '',
        );
    }
    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'due_date' => $this->due_date,
            'payment_date' => $this->payment_date,
            'product_id' => $this->product_id,
            'section_id' => $this->section_id,
            'collection_amount' => $this->collection_amount,
            'commission_amount' => $this->commission_amount,
            'discount' => $this->discount,
            'VAT_rate' => $this->VAT_rate,
            'VAT_value' => $this->VAT_value,
            'total' => $this->total,
            'state' => $this->state,
            'note' => $this->note,
        ];
    }

    private static function ToInvoiceState($value)
    {
        if (is_null($value))
            return InvoiceState::unPaid;

        if ($value instanceof InvoiceState)
            return $value;

        return InvoiceState::from($value);
    }
    private static function VATRateToFloat($value)
    {
        if (is_float($value))
            return $value;

        return (float)(string)Str::of($value)->after('%');
    }
}
