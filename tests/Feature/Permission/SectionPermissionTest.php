<?php

namespace Tests\Feature\Permission;

use App\Http\Controllers\SectionController;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;
use Tests\UserTestBuilder;
use Mockery\MockInterface;

class SectionPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected MockInterface $spyUser;
    public function setUp(): void
    {
        parent::setUp();

        (new PermissionRoleTestFactory)->createPermissions([
            'create section',
            'show section',
            'delete section',
            'edit section'
        ]);
        $this->spyUser = $this->spy(User::class);
    }

    /** @test */
    public function user_is_asked_if_he_has_show_section_permission_on_route_sections_index(): void
    {

        $authorizedUser = (new UserTestBuilder)->create($this->spyUser);

        $this->signIn($authorizedUser)
            ->get(route('sections.index'));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('show section')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_create_section_permission_on_route_sections_store(): void
    {

        $authorizedUser = (new UserTestBuilder)->create($this->spyUser);

        $section = Section::factory()->make();
        $this->signIn($authorizedUser)
            ->post(route('sections.store'), $section->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('create section')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_edit_section_permission_on_route_sections_update(): void
    {

        $authorizedUser = (new UserTestBuilder)->create($this->spyUser);

        $section = Section::factory()->create();


        $this->signIn($authorizedUser)
            ->put(route('sections.update', ['section' => $section->id]), $section->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('edit section')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_delete_section_permission_on_route_sections_delete(): void
    {

        $authorizedUser = (new UserTestBuilder)->create($this->spyUser);

        $section = Section::factory()->create();

        $this->signIn($authorizedUser)
            ->delete(route('sections.destroy', ['section' => $section->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('delete section')->once();
    }
}
