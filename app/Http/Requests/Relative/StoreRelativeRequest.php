<?php

namespace App\Http\Requests\Relative;

use Illuminate\Foundation\Http\FormRequest;

class StoreRelativeRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user field is required.',
            'user_id.exists' => 'The selected user does not exist.',

            'name.required' => 'The relative name is required.',
            'name.string' => 'The name must be a string.',

            'relation.required' => 'The relation field is required.',
            'relation.string' => 'The relation must be a string.',

            'phone_number.required' => 'phone_number is required.',
            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',

            'email.required' => 'email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = $this->input('user_id');

            if ($userId) {
                $user = \App\Models\User::find($userId);

                if (!$user || $user->role !== 'relative') {
                    $validator->errors()->add('user_id', 'The selected user must have the role "relative".');
                }
            }
        });
    }
}
