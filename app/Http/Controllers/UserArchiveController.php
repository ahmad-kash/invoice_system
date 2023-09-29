<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserArchiveController extends Controller
{

    public function __construct(private UserService $userService)
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        return view(
            'user.archive.index',
            [
                'users' => $this->userService->getAllWithPagination(onlyTrashed: true)
            ]
        );
    }

    public function update(User $user)
    {
        $this->userService->restore($user);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $this->userService->forceDelete($user);

        return redirect()->back();
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'restore',
            'update' => 'restore',
            'destroy' => 'forceDelete',
        ];
    }
}
