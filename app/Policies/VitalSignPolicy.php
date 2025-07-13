<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VitalSign;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;

class VitalSignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, VitalSign $vitalSign): bool
    {
        Log::info('ENTERED POLICY VIEW', [
            'user_id' => $user->id,
            'patient_id' => $vitalSign->patient_id,
            'viewAny',
            $user->id === $vitalSign->patient_id
        ]);

        // dd([
        //     'user_id' => $user->id,
        //     'related_user_id' => $vitalSign->patient->relative?->user_id,
        // ]);

        // إذا كان المستخدم هو صاحب المؤشر الحيوي
        if ($user->id === $vitalSign->patient_id) {
            // $user->id === $vitalSign->patient_id
            return true;
        }

        // إذا كان المستخدم أدمن
        if ($user->role === 'admin') {
            return true;
        }

        // إذا كان المستخدم دكتور مشرف على المريض
        if ($user->role === 'doctor' && $vitalSign->patient->doctors->contains('user_id', $user->id)) {
            return true;
        }
        // dd($user->id);
        // إذا كان المستخدم قريب مرتبط بالمريض
        if ($user->role === 'relative' && $vitalSign->patient->relative?->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VitalSign $vitalSign): bool
    {

        Log::info('ENTERED POLICY VIEW', [
            'user_id' => $user->id,
            'patient_user_id' => $vitalSign->patient->user_id,
            "view",

        ]);

        if ($user->id === $vitalSign->patient->user_id) {
            return true;
        }

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'doctor' && $vitalSign->patient->doctors->contains('user_id', $user->id)) {
            return true;
        }
        // dd($user->id);
        if ($user->role === 'relative' && $vitalSign->patient->relative?->user_id === $user->id) {
            return true;
        }

        return false;
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VitalSign $vitalSign): bool
    {
        // dd($user->id);
        return $user->id === $vitalSign->patient->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VitalSign $vitalSign): bool
    {
        return $user->id === $vitalSign->patient->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VitalSign $vitalSign): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VitalSign $vitalSign): bool
    {
        return false;
    }
}
