<?php

namespace Tests\Feature\Dashboard;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\DashboardTestCase;

use function PHPUnit\Framework\assertJson;

class ProductTest extends DashboardTestCase
{

    public function getPermissions(): array
    {
        return
            [
                'create product',
                'show product',
                'delete product',
                'edit product'
            ];
    }

    /** @test */
    public function user_can_see_all_products(): void
    {
        $products = Product::factory(5)->create();

        $this->get(route('products.index'))
            ->assertOk()
            ->assertViewHas('products', function ($paginator) use ($products) {
                return $this->eloquentCollectionsAreEqual(collect($paginator->items()), $products);
            })
            ->assertSeeInOrder($products->map(fn ($product) => $product->name)->toArray());
    }

    /** @test */
    public function user_can_see_no_product_found_message_if_there_is_not_any_products_in_database(): void
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('لا يوجد منتجات');
    }

    /** @test */
    public function user_can_see_edit_product_page(): void
    {
        $product = Product::factory()->create();
        $this->get(route('products.edit', ['product' => $product->id]))
            ->assertOk()
            ->assertSee('تعديل المنتج')
            ->assertViewHas('product', $product);
    }
    /** @test */
    public function user_can_see_create_product_page(): void
    {
        $this->get(route('products.create'))
            ->assertOk()
            ->assertSee('أضافة منتج جديد');
    }
    /** @test */
    public function user_can_see_no_sections_found_message(): void
    {
        $this->get(route('products.create'))
            ->assertOk()
            ->assertSee('رجاء قم باضافة قسم');
    }
    /** @test */
    public function user_can_see_sections_in_a_list(): void
    {
        $sections = Section::factory(2)->create();

        // in create page
        $this->get(route('products.create'))
            ->assertOk()
            ->assertViewHas('sections', function ($sectionsInView) use ($sections) {
                return $this->eloquentCollectionsAreEqual($sectionsInView, $sections);
            })
            ->assertSeeInOrder($sections->map(fn ($section) => $section->name)->toArray());

        // in edit page
        $product = Product::factory()->create();
        $this->get(route('products.edit', ['product' => $product->id]))
            ->assertOk()
            ->assertViewHas('sections', function ($sectionsInView) use ($sections) {
                return $this->eloquentCollectionsAreEqual($sectionsInView, $sections);
            })
            ->assertSeeInOrder($sections->map(fn ($section) => $section->name)->toArray());
    }

    /** @test */
    public function user_can_create_product(): void
    {
        $product = Product::factory()->make();
        $this->post(route('products.store'), $product->toArray())
            ->assertRedirectToRoute('products.index');

        $product->refresh();
        $this->assertDatabaseHas('products', ['name' => $product->name, 'description' => $product->description]);
    }


    /** @test */
    public function user_can_edit_product(): void
    {
        $product = Product::factory()->create();

        $this->put(route('products.update', ['product' => $product->id]), ['name' => 'test', 'description' => 'test', 'section_id' => $product->section_id])
            ->assertRedirectToRoute('products.index');

        $product->refresh();
        $this->assertDatabaseHas('products', ['name' => 'test', 'description' => 'test']);
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForProduct
     */
    public function a_user_can_not_create_product_with_invalid_data(string $fieldName, $name = null, $description = null): void
    {
        $section_id = 1;
        $this->post(route('products.store'), ['name' => $name, 'description' => $description, 'section_id' => $section_id])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForProduct
     */
    public function a_user_can_not_edit_product_with_invalid_data(string $fieldName, $name = null, $description = null, $section_id = null): void
    {
        $product = Product::factory()->create();
        $this->put(route('products.update', ['product' => $product->id]), ['name' => $name, 'description' => $description, 'section_id' => $section_id])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForProduct()
    {
        $faker = self::getFaker();

        return [
            'description in not a string' =>
            ['description', 'name' => $faker->sentence(), 'description' => 5, 'section_id' => 1],
            'name is null' =>
            ['name', 'name' => null, 'description' => $faker->sentence(), 'section_id' => 1],
            'name is not string' =>
            ['name', 'name' => 1, 'description' => null, 'section_id' => 1],
            'name is greater then 999 character' =>
            ['name', 'name' => str()->random(1000), 'description' => $faker->sentence(), 'section_id' => 1],
            'section_id is null' =>
            ['section_id', 'name' => $faker->sentence(), 'description' => $faker->sentence(), 'section_id' => null],
            'section_id does not exist in db' =>
            ['section_id', 'name' => $faker->sentence(), 'description' => $faker->sentence(), 'section_id' => 1000],
        ];
    }

    /** @test */
    public function user_can_delete_product(): void
    {

        $product = Product::factory()->create();

        $this->delete(route('products.destroy', ['product' => $product->id]))
            ->assertRedirectToRoute('products.index');
    }

    /** @test */
    public function user_can_get_products_that_belong_to_a_section(): void
    {
        $section = Section::factory()->create();
        $products = Product::factory(10)->create(['section_id' => $section->id]);
        $this->withoutExceptionHandling();

        $this->get(route('sections.products', ['section' => $section->id]))
            ->assertOk()
            ->assertJson(
                function (AssertableJson $json) use ($products) {
                    return $json->has(10)
                        ->first(
                            function (AssertableJson $json) use ($products) {
                                return $json->where('id', $products[0]->id)
                                    ->where('name', $products[0]->name)
                                    ->etc();
                            }
                        );
                }
            );
    }
}
