<?php

namespace App\Services\User;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\Result\ServiceResult;

class UserService
{
    /**
     * Create a new user with hashed password.
     *
     * @param array $data User data including name, email, password, role.
     */
    public function create(array $data)
    {
        try {

            // Hash the plain text password before saving
            $data['password'] = Hash::make($data['password']);

            // Create and return the user
          $user= User::create($data);
             return new ServiceResult(true, $user);
        } catch (Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to create user");
        }
       
    }
    /**
     * Update an existing user with the given data.
     * If password is included, it will be hashed.
     *
     * @param User $user The user instance to update
     * @param array $data Updated data
     */
    public function update(User $user, array $data)
    {
        try {

            // If password is present, hash it before saving
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Remove password key to avoid overwriting with null
                unset($data['password']);
            }

            // Update the user and return it
            $user->update($data);
           return new ServiceResult(true, $user);
        } catch (Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to update user");
        }
    }

    /**
     * Delete a given user from the database.
     *
     * @param User $user The user instance to delete
     */
    public function delete(User $user)
    {
        try {

            // Delete the user
            $user->delete();
        return new ServiceResult(true, $user);
        } catch (Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to delete user");
        }
    }

    public function getUser(int $id): ServiceResult
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return new ServiceResult(false, null, "User not found");
            }
            return new ServiceResult(true, $user);
        } catch (\Exception $e) {
            return new ServiceResult(false, null, "Error: " );
        }
    }
}
