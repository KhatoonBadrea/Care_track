<?php

namespace App\Http\Requests\VitalSign;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitalSignRequest extends FormRequest
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
            // 'patient_id' => 'required|exists:patients,id',
            'temperature' => 'required|numeric|between:34,42',
            'heart_rate' => 'required|integer|between:30,200',
            'blood_pressure_systolic' => 'required|integer|between:70,200',
            'blood_pressure_diastolic' => 'required|integer|between:40,130',
            'respiratory_rate' => 'required|integer|between:10,60',
            // 'measured_at' => 'required|date',
        ];
    }


     protected function prepareForValidation(): void
    {
        if ($this->has('measured_at')) {
            $formattedDate = date('Y-m-d H:m:s', strtotime($this->measured_at));
            $this->merge([
                'measured_at' => $formattedDate,
            ]);
        }
    }

     /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
           'patient_id.required' => 'Patient ID is required.',
            'patient_id.exists' => 'Patient not found.',

            'temperature.required' => 'Temperature is required.',
            'temperature.numeric' => 'Temperature must be a numeric value.',
            'temperature.between' => 'Temperature must be between 34°C and 42°C.',

            'heart_rate.required' => 'Heart rate is required.',
            'heart_rate.integer' => 'Heart rate must be an integer.',
            'heart_rate.between' => 'Heart rate must be between 30 and 200 bpm.',

            'blood_pressure_systolic.required' => 'Systolic blood pressure is required.',
            'blood_pressure_systolic.integer' => 'Systolic pressure must be an integer.',
            'blood_pressure_systolic.between' => 'Systolic pressure must be between 70 and 200.',

            'blood_pressure_diastolic.required' => 'Diastolic blood pressure is required.',
            'blood_pressure_diastolic.integer' => 'Diastolic pressure must be an integer.',
            'blood_pressure_diastolic.between' => 'Diastolic pressure must be between 40 and 130.',

            'respiratory_rate.required' => 'Respiratory rate is required.',
            'respiratory_rate.integer' => 'Respiratory rate must be an integer.',
            'respiratory_rate.between' => 'Respiratory rate must be between 10 and 60.',

            'measured_at.required' => 'Measurement date and time is required.',
            'measured_at.date' => 'Measurement date must be a valid date.',
            'measured_at.before_or_equal' => 'Measurement date cannot be in the future.',
        ];
    }

}
