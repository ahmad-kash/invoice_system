<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{


    public function __construct(protected UserService $userService)
    {
        $this->authorizeResource(User::class, 'user');
    }

    protected function resourceAbilityMap()
    {

        return [
            'index' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
            'forceDestroy' => 'forceDelete',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index', ['users' => $this->userService->getAllWithPagination()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create', ['roles' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create(
            UserDTO::fromArray($request->validated()),
            $request->validated('role')
        );

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        return view('user.edit', ['user' => $user, 'roles' => Role::all()]);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, UserDTO::fromUpdateRequest($request->validated(), $user), $request->validated('role'));

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return redirect()->route('users.index');
    }
}
