<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belong_to_section(): void
    {
        $productSection = Section::factory()->create();
        $product = Product::factory()->create(['section_id' => $productSection->id]);

        $this->assertInstanceOf(Section::class, $product->section);

        $this->assertEquals($product->section->id, $productSection->id);
    }

    /** @test */
    public function it_has_a_name(): void
    {
        $product = Product::factory()->create(['name' => 'test']);

        $this->assertIsString($product->name);
        $this->assertEquals($product->name, 'test');
    }
}
