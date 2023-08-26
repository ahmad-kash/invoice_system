<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_creator(): void
    {
        $creator = User::factory()->create();
        $section = Section::factory()->create(['user_id' => $creator->id]);

        $this->assertInstanceOf(User::class, $section->creator);
        $this->assertEquals($creator->id, $section->creator->id);
    }
    /** @test */
    public function it_has_products(): void
    {
        $section = Section::factory()->create();
        $product = Product::factory()->create(['section_id' => $section->id]);

        $this->assertTrue($section->products->contains($product));
    }
}
