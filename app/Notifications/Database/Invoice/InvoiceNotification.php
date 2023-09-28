<?php

namespace App\Notifications\Database\Invoice;

use App\Models\Invoice;
use Illuminate\Foundation\Auth\User;
use App\Notifications\Database\DatabaseNotification;

abstract class InvoiceNotification extends DatabaseNotification
{
    public function __construct(private Invoice $invoice, private User $user)
    {
    }

    public function toDatabase(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->number,
            'user_name' => $this->user->name,

        ] + $this->additionalData();
    }
    function additionalData(): array
    {
        return [];
    }
}
