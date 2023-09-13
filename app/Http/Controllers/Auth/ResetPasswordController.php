<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResetPasswordRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{

    public function __construct(protected UserService $userService)
    {
    }
    public function create(Request $request)
    {
        return view('auth.passwords.reset', ['email' => $request->input('email')]);
    }

    public function store(StoreResetPasswordRequest $request)
    {
        $user = $this->userService->setNewPassword($request->input('email'), $request->input('password'));

        Auth::login($user);

        return redirect()->route('home');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('reset-password');
        $this->userService->resetPassword($user);

        return redirect()->back();
    }
}
