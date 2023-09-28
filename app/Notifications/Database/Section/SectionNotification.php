<?php

namespace App\Notifications\Database\Section;

use App\Models\Section;
use App\Models\User;
use App\Notifications\Database\DatabaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

abstract class SectionNotification extends DatabaseNotification
{
    public function __construct(private Section $section, private User $user)
    {
    }

    public function toDatabase(): array
    {
        return [
            'section_id' => $this->section->id,
            'section_name' => $this->section->name,
            'user_name' => $this->user->name,

        ] + $this->additionalData();
    }
}
