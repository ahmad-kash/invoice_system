<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function getUserNameAttribute(): string
    {
        return $this->user->name;
    }
    protected function getPathAttribute(): string
    {
        return $this->invoice->sectionName . '/' . $this->invoice->number . '/' . $this->hash_name;
    }
}
