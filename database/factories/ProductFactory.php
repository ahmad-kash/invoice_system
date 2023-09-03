<?php

namespace Database\Factories;

use App\Models\Section;
use Database\Factories\BaseFactory as Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(),
            'section_id' => $this->getModelId(Section::class),
        ];
    }
}
