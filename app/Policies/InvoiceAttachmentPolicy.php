<?php

namespace App\Policies;

use App\Models\InvoiceAttachment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoiceAttachmentPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InvoiceAttachment $invoiceAttachment): bool
    {
        return $user->hasPermissionTo('show invoice');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create invoice');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InvoiceAttachment $invoiceAttachment): bool
    {
        return $user->hasPermissionTo('delete invoice');
    }
}
