<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new user with hashed password.
     *
     * @param array $data User data including name, email, password, role, etc.
     * @return User Newly created User instance
     */
    public function create(array $data): User
    {
        try {

            // Hash the plain text password before saving
            $data['password'] = Hash::make($data['password']);

            // Create and return the user
            return User::create($data);
        } catch (Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            throw new Exception('Failed to create user.');
        }
    }
    /**
     * Update an existing user with the given data.
     * If password is included, it will be hashed.
     *
     * @param User $user The user instance to update
     * @param array $data Updated data
     * @return User Updated User instance
     */
    public function update(User $user, array $data): User
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
            return $user;
        } catch (Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage());
            throw new Exception('Failed to update user.');
        }
    }

    /**
     * Delete a given user from the database.
     *
     * @param User $user The user instance to delete
     * @return void
     */
    public function delete(User $user): void
    {
        try {

            // Delete the user
            $user->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete user : ' . $e->getMessage());
            throw new Exception('Failed to delete user .');
        }
    }
}
