<?php

namespace App\Models;

use App\Enums\InvoiceState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'state' => InvoiceState::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getUserNameAttribute()
    {
        return $this->user->name;
    }
}
