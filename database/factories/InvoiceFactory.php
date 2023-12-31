<?php

namespace Database\Factories;

use App\Enums\InvoiceState;
use App\Models\Product;
use App\Models\Section;
use Database\Factories\BaseFactory as Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->bothify('???????'),
            'product_id' => $this->getModelId(Product::class),
            'section_id' => $this->getModelId(Section::class),
            'state' => InvoiceState::unPaid,
            'collection_amount' => $amount = $this->faker->randomNumber(4),
            'commission_amount' => $this->faker->randomNumber(3),
            'discount' => $this->faker->randomNumber(2),
            'VAT_value' => $amount - $this->faker->randomNumber(2),
            'VAT_rate' => '%5',
            'total' => $this->faker->randomNumber(4),
            'note' => $this->faker->sentence(),
            'due_date' => $this->faker->date(),
            'payment_date' => $this->faker->date(),

        ];
    }
}
