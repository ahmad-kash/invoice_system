<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ActivationController extends Controller
{

    public function index()
    {
        return view('auth.is-not-active');
    }
}
