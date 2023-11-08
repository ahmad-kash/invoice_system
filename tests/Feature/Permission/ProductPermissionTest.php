<?php

namespace Tests\Feature\Permission;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;
use Tests\UserTestBuilder;

class ProductPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $spyUser;

    public function setUp(): void
    {
        parent::setUp();

        (new PermissionRoleTestFactory)->createPermissions([
            'create product',
            'show product',
            'delete product',
            'edit product'
        ]);

        $this->spyUser = $this->spy(User::class, function (MockInterface $mock) {
            $mock->shouldReceive('isActive')->andReturn(true);
        });

        $this->signIn($this->spyUser);
    }

    /** @test */
    public function user_is_asked_if_he_has_show_product_permission_on_route_products_index(): void
    {
        $this->get(route('products.index'));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('show product')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_create_product_permission_on_route_products_store(): void
    {
        $product = Product::factory()->make();

        $this->post(route('products.store'), $product->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('create product')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_edit_product_permission_on_route_products_update(): void
    {
        $product = Product::factory()->create();

        $this->put(route('products.update', ['product' => $product->id]), $product->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('edit product')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_delete_product_permission_on_route_products_delete(): void
    {
        $product = Product::factory()->create();

        $this->delete(route('products.destroy', ['product' => $product->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('delete product')->once();
    }
}
