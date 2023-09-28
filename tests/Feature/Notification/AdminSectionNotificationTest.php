<?php

namespace Tests\Feature\Notification;

use App\Models\Section;
use App\Notifications\Database\Section\SectionCreated;
use App\Notifications\Database\Section\SectionDeleted;
use App\Notifications\Database\Section\SectionUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\NotificationTestCase;


class AdminSectionNotificationTest extends NotificationTestCase
{
    use RefreshDatabase;

    protected Section $section;
    public function getUserPermissions(): array
    {
        return [
            'create section', 'edit section', 'delete section',
        ];
    }
    public function setUp(): void
    {
        parent::setUp();

        $this->section = Section::factory()->create();
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_section_is_created(): void
    {
        $this->post(route('sections.store'), $this->section->toArray());

        Notification::assertSentTo($this->admins, SectionCreated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_section_is_updated(): void
    {
        $this->put(
            route('sections.update', ['section' => $this->section->id]),
            ['name' => 'test']
        );

        Notification::assertSentTo($this->admins, SectionUpdated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_section_is_deleted(): void
    {
        //delete the section
        $this->delete(
            route('sections.destroy', ['section' => $this->section->id]),
            $this->section->toArray()
        );

        Notification::assertSentTo($this->admins, SectionDeleted::class);
    }
}
