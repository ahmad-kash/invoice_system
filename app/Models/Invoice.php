<?php

namespace App\Models;

use App\Enums\InvoiceState;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'state' => InvoiceState::class
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function paymentDetails(): HasMany
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function attachment(): HasMany
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    public function __get($key)
    {
        if ($key === 'sectionName')
            return $this->section->name;

        return parent::__get($key);
    }
}
