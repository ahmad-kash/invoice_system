<?php

namespace Tests\Feature\Dashboard;

use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;
use Tests\UserTestBuilder;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $permissionFactory = new PermissionRoleTestFactory;
        $sectionPermissions = [
            'create section',
            'show section',
            'delete section',
            'edit section'
        ];
        $permissionFactory->createPermissions($sectionPermissions);
        $permissionFactory->createRoles(['admin']);
        $permissionFactory->assignPermissionsToRole('admin', $sectionPermissions);

        $this->user = (new UserTestBuilder)->withRoles('admin')->create();
        $this->signIn($this->user);
    }

    /** @test */
    public function user_can_see_all_sections(): void
    {
        $this->withoutExceptionHandling();
        $sections = Section::factory(5)->create();
        $this->get(route('sections.index'))
            ->assertOk()
            ->assertViewHas('sections', Section::paginate(5))
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
    }


    /** @test */
    public function user_can_edit_section(): void
    {
        $section = Section::factory()->create();

        $this->put(route('sections.update', ['section' => $section->id]), $section->toArray())
            ->assertRedirectToRoute('sections.index');
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
