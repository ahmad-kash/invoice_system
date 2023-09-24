<?php

namespace App\Enums;

enum InvoiceState: int
{
    case paid = 1;
    case unPaid = 2;
    case partiallyPaid = 3;

    public function label()
    {
        return $this->name;
    }
    public static function fromString($strState): ?InvoiceState
    {
        return match (strtolower($strState)) {
            strtolower('unPaid') => InvoiceState::unPaid,
            strtolower('paid') => InvoiceState::paid,
            strtolower('partiallyPaid') => InvoiceState::partiallyPaid,
            default => null
        };
    }
}
