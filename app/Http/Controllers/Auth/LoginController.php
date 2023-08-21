<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function create()
    {
        return view('auth.login');
    }
    public function store(StoreUserRequest $request)
    {

        if (auth()->attempt($request->validated())) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'هنالك خطأ في البريد الاكتروني او كلمة المرور',
        ])->onlyInput('email');
    }
    public function destroy(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login.create');
    }
}
