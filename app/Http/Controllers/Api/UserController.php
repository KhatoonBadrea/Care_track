<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Inject the UserService.
     *
     * @param UserService $userService
     */
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a list of all users.
     *
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in the database.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return $this->success(new UserResource($user));
    }

    /**
     * Display a specific user.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return $this->success(new UserResource($user));
    }

    /**
     * Update an existing user's information.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->update($user, $request->validated());
        return $this->success(new UserResource($user), 'User updated successfully', 200);
    }

    /**
     * Delete a user from the database.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return $this->success(null, 'User deleted successfully', 200);
    }
}
