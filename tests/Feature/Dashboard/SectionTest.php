<?php

namespace Tests\Feature\Dashboard;

use App\Models\Section;
use App\Models\User;
use Tests\DashboardTestCase;
use Tests\PermissionRoleTestFactory;
use Tests\UserTestBuilder;

class SectionTest extends DashboardTestCase
{
    public function getPermissions(): array
    {
        return [
            'create section',
            'show section',
            'delete section',
            'edit section'
        ];
    }
    /** @test */
    public function user_can_see_all_sections(): void
    {
        $sections = Section::factory(5)->create();
        $this->get(route('sections.index'))
            ->assertOk()
            ->assertViewHas('sections', function ($paginator) use ($sections) {
                return $this->eloquentCollectionsAreEqual(collect($paginator->items()), $sections);
            })
            ->assertSeeInOrder($sections->map(fn ($section) => $section->name)->toArray());
    }

    /** @test */
    public function user_can_see_no_section_found_message_if_there_is_not_any_sections_in_database(): void
    {
        $this->get(route('sections.index'))
            ->assertOk()
            ->assertSee('لا يوجد اقسام');
    }

    /** @test */
    public function user_can_see_edit_section_page(): void
    {
        $section = Section::factory()->create();
        $this->get(route('sections.edit', ['section' => $section->id]))
            ->assertOk()
            ->assertSee('تعديل القسم')
            ->assertViewHas('section', $section);
    }
    /** @test */
    public function user_can_see_create_section_page(): void
    {
        $this->get(route('sections.create'))
            ->assertOk()
            ->assertSee('أضافة قسم جديد');
    }

    /** @test */
    public function user_can_create_section(): void
    {
        $section = Section::factory()->make();

        $this->post(route('sections.store'), $section->toArray())
            ->assertRedirectToRoute('sections.index');

        $this->assertDatabaseHas('sections', $section->toArray());
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForSection
     */
    public function a_user_can_not_create_section_with_invalid_data(string $fieldName, $name = null, $description = null): void
    {
        $this->post(route('sections.store'), ['name' => $name, 'description' => $description])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForSection
     */
    public function a_user_can_not_edit_section_with_invalid_data(string $fieldName, $name = null, $description = null): void
    {
        $section = Section::factory()->create();
        $this->put(route('sections.update', ['section' => $section->id]), ['name' => $name, 'description' => $description])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForSection()
    {
        $faker = self::getFaker();

        return [
            'description in not a string' => ['description', 'name' => $faker->sentence(), 'description' => 5],
            'name is null' => ['name', 'name' => null, 'description' => $faker->sentence()],
            'name is not string' => ['name', 'name' => 1, 'description' => null],
            'name is greater then 999 character' => ['name', 'name' => str()->random(1000)]
        ];
    }

    /** @test */
    public function user_can_edit_section(): void
    {
        $section = Section::factory()->create();

        $this->put(route('sections.update', ['section' => $section->id]), $section->toArray())
            ->assertRedirectToRoute('sections.index');

        $section->refresh();
        $this->assertDatabaseHas('sections', ['name' => $section->name, 'description' => $section->description]);
    }

    /** @test */
    public function user_can_delete_section(): void
    {

        $section = Section::factory()->create();

        $this->delete(route('sections.destroy', ['section' => $section->id]))
            ->assertRedirectToRoute('sections.index');
    }

    // TODO add section show page

    // /** @test */
    // public function authorized_user_can_see_section_show_page(): void
    // {
    //     $this->withoutExceptionHandling();
    //     $authorizedUser = (new UserTestBuilder)->withPermissions('show section')->create();
    //     $section = Section::factory()->create();
    //     $this->signIn($authorizedUser)
    //         ->get(route('sections.show', ['section' => $section->id]))
    //         ->assertOk();
    // }

}
