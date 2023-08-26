<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function sectionName()
    {
        return $this->section->name;
    }
}
