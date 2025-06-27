<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'Specialization' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = $this->input('user_id');

            if ($userId) {
                $user = \App\Models\User::find($userId);

                if (!$user || $user->role !== 'doctor') {
                    $validator->errors()->add('user_id', 'The selected user must have the role "doctor".');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'The selected user does not exist.',


            'Specialization.string' => 'Specialization must be a string.',

            'phone_number.string' => 'The phone number must be a string.',
            'phone_number.max' => 'The phone number may not be greater than 20 characters.',

        ];
    }
}
