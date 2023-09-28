<?php

namespace App\Notifications\Database\Product;

use App\Models\Product;
use App\Models\User;
use App\Notifications\Database\DatabaseNotification;

abstract class ProductNotification extends DatabaseNotification
{
    public function __construct(private Product $product, private User $user)
    {
    }

    public function toDatabase(): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'user_name' => $this->user->name,

        ] + $this->additionalData();
    }
}
