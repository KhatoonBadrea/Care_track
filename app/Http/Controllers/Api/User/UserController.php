<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\User\UserService;
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
        $users = User::select('name', 'email','role')->get();
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
        // $user= $this->success(new UserResource($user));
        if ($user->success) {
            return $this->success([
                'message' => 'user created successfully',
                'data' => new UserResource($user->data)
            ]);
        } else {
            return $this->error(
                null,
                $user->message,
                401
            );
        }
    }

    /**
     * Display a specific user.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(int $id)
    {
        $result = $this->userService->getUser($id);

        if ($result->success) {
            return $this->success([
                'message' => 'this is the selected user',
                'data' => new UserResource($result->data)
            ]);
        } else {
            return $this->error(
                null,
                $result->message,
                404
            );
        }
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
        // return $this->success(new UserResource($user), 'User updated successfully', 200);
      if ($user->success) {
            return $this->success([
                'message' => 'user update successfully',
                'data' => new UserResource($user->data)
            ]);
        } else {
            return $this->error(
                null,
                $user->message,
                401
            );
        }   
    }
    /**
     * Delete a user from the database.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
      $deletion=$this->userService->delete($user);
        return $deletion->success
            ? $this->success([
                'message' => 'user deleted successfully',
                'data' => new UserResource($deletion->data)
            ])
            : $this->error(null, $deletion->message, 401);
    }
}
