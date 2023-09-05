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
}
