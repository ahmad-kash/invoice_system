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

    protected $additionalAttributeMap = ['sectionName' => 'getSectionName', 'productName' => 'getProductName'];
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

    public function getSectionName()
    {
        return $this->section->name;
    }

    public function getProductName()
    {
        return $this->product->name;
    }

    public function __get($key)
    {
        if (in_array($key, array_keys($this->additionalAttributeMap))) {
            $method = $this->additionalAttributeMap[$key];
            return $this->$method();
        }


        return parent::__get($key);
    }
}
