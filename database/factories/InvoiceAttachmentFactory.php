<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use Database\Factories\BaseFactory as Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceAttachment>
 */
class InvoiceAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->getModelId(Invoice::class),
            'user_id' => $this->getModelId(User::class),
            'hash_name' => $this->faker->filePath(),
            'name' => $this->faker->bothify('???????') . '.' . $this->faker->fileExtension(),
        ];
    }
}
