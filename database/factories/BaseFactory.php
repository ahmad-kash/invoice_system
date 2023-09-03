<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentDetail>
 */
abstract class BaseFactory extends Factory
{
    public function getModelId(string $model)
    {
        return $model::inRandomOrder()?->first()?->id ?? $model::factory()->create()->id;
    }
}
