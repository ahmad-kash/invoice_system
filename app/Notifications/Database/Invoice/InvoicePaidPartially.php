<?php

namespace App\Notifications\Database\Invoice;

use App\Models\Invoice;
use Illuminate\Foundation\Auth\User;
use App\Notifications\Database\DatabaseNotification;

class InvoicePaidPartially extends InvoiceNotification
{
    public function __construct(private Invoice $invoice, private User $user, private float $amount)
    {
        parent::__construct($invoice, $user);
    }

    public function additionalData(): array
    {
        return [
            'amount' => $this->amount
        ];
    }
}
