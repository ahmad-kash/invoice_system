<?php

namespace App\Services\Mail;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class MailService
{

    public function sendWelcomeMailTo(User $user)
    {

        Mail::to($user)->send(new WelcomeMail($user, URL::signedRoute('password.reset.create', ['email' => $user->email])));
    }
}
