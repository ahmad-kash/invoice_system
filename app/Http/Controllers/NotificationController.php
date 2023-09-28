<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show(DatabaseNotification $notification, Request $request)
    {
        $notification->markAsRead();

        return redirect(to: $request->query('url'));
    }

    public function showAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
