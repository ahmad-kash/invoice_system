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

    public function __get($key)
    {
        if ($key === 'path')
            return $this->invoice->sectionName . '/' . $this->invoice->number . '/' . $this->hash_name;

        return parent::__get($key);
    }
}
