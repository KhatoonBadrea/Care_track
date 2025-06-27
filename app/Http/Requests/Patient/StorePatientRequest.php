<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'relative_id' => 'nullable|exists:relatives,id',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'notes' => 'nullable|string',
            'blood_clique_id' => 'exists:blood_cliques,id',
            'doctor_ids' => 'nullable|array',
            'doctor_ids.*' => 'exists:users,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('birth_date')) {
            $formattedDate = date('Y-m-d', strtotime($this->birth_date));
            $this->merge([
                'birth_date' => $formattedDate,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user does not exist.',

            'relative_id.exists' => 'The selected relative does not exist.',

            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be either Male or Female.',

            'birth_date.required' => 'Birth date is required.',
            'birth_date.date' => 'Birth date must be a valid date.',

            'notes.string' => 'Notes must be a string.',

            'blood_clique_id.exists' => 'The selected blood clique does not exist.',

            'doctor_ids.array' => 'Doctor IDs must be an array.',
            'doctor_ids.*.exists' => 'One or more selected doctors do not exist.',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = $this->input('user_id');

            if ($userId) {
                $user = \App\Models\User::find($userId);

                if (!$user || $user->role !== 'patient') {
                    $validator->errors()->add('user_id', 'The selected user must have the role "patient".');
                }
            }
        });
    }
}
