<?php

namespace Modules\User\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\User\app\Http\Requests\RegisterRequest;
use Modules\User\app\Http\Requests\UpdateUserRequest;
use Modules\User\app\Models\User;
use Modules\User\app\Repository\UserRepository;
use Modules\User\app\Resources\UserCollection;
use Modules\User\app\Resources\UserResource;
use Modules\User\app\Services\UserService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserRepository $repository): UserCollection
    {
        return new UserCollection($repository->paginate(5));
    }

    /**
     * Display a specific user.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(RegisterRequest $request, UserService $userService): UserResource
    {
        return new UserResource($userService->createUser($request->email, $request->password));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(User $user, UpdateUserRequest $request, UserService $userService): UserResource
    {
        return new UserResource($userService->updateUser($user, $request));
    }
}
