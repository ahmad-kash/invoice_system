<?php

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Dispatcher;

class AdminNotifyService
{
    public function notifyAdmins(Notification $notification)
    {
        Dispatcher::send($this->getSystemAdmins(), $notification);
    }

    private function getSystemAdmins()
    {
        return User::role('admin')->get();
    }
}
