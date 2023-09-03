<?php

namespace Database\Factories;

use App\Models\User;
use Database\Factories\BaseFactory as Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'user_id' => $this->getModelId(User::class),
        ];
    }
}
