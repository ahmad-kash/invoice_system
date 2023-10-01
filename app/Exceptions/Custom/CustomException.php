<?php

namespace App\Exceptions\Custom;

use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    public function render(Request $request)
    {
        return back()->with('errorMessage', $this->message);
    }
}
