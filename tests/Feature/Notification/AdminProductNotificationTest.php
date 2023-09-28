<?php

namespace Tests\Feature\Notification;

use App\Models\Product;
use App\Notifications\Database\Product\ProductCreated;
use App\Notifications\Database\Product\ProductDeleted;
use App\Notifications\Database\Product\ProductUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\NotificationTestCase;

class AdminProductNotificationTest extends NotificationTestCase
{
    use RefreshDatabase;

    protected Product $product;
    public function getUserPermissions(): array
    {
        return [
            'create product', 'edit product', 'delete product',
        ];
    }
    public function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_product_is_created(): void
    {
        $this->post(route('products.store'), ['section_id' => $this->product->section->id] + $this->product->toArray());

        Notification::assertSentTo($this->admins, ProductCreated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_product_is_updated(): void
    {
        $this->put(
            route('products.update', ['product' => $this->product->id]),
            ['name' => 'test'] + ['section_id' => $this->product->section->id]
        );

        Notification::assertSentTo($this->admins, ProductUpdated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_product_is_deleted(): void
    {
        //delete the product
        $this->delete(
            route('products.destroy', ['product' => $this->product->id])
        );

        Notification::assertSentTo($this->admins, ProductDeleted::class);
    }
}
