<?php

namespace App\Http\Controllers\Api\Patient;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Services\Patient\PatientService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\Patient\PatientResource;
use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\AssignDoctorsRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\Patient\AssignDoctorsResource;

class PatientController extends Controller
{

    public function __construct(private PatientService $patientService) {}



    public function index()
    {
        $patients = Patient::with(['user', 'bloodClique', 'doctors', 'relative'])->get();
        return PatientResource::collection($patients);
    }




    public function store(StorePatientRequest $request)
    {

        $patient = $this->patientService->create($request->validated());

        if ($patient->success) {
            return $this->success([
                'message' => 'patient created successfully',
                'data' => new PatientResource($patient->data)
            ]);
        } else {
            return $this->error(
                null,
                $patient->message,
                401
            );
        }
    }




    public function assignDoctors(Request $request, Patient $patient)
    {
        $doctorIds = $request->input('doctor_ids', []);

        $doctors = $this->patientService->assignDoctors($patient, $doctorIds);

        if ($doctors->success) {
            return $this->success(new AssignDoctorsResource($doctors->data));
        } else {
            return $this->error(
                null,
                $doctors->message,
                401
            );
        }
    }




    public function show(Patient $patient)
    {
        $patient->load(['user', 'bloodClique', 'doctors', 'relative']);
        return $this->success(new PatientResource($patient));
    }




    public function update(UpdatePatientRequest $request, Patient $patient)
    {

        $patient = $this->patientService->update($patient, $request->validated());
        if ($patient->success) {
            return $this->success([
                'message' => 'patient update successfully',
                'data' => new PatientResource($patient->data)
            ]);
        } else {
            return $this->error(
                null,
                $patient->message,
                401
            );
        }
    }




    public function destroy(Patient $patient)
    {
        $deletion = $this->patientService->delete($patient);
        return $deletion->success
            ? $this->success([
                'message' => 'patient deleted successfully',
                'data' => new PatientResource($deletion->data)
            ])
            : $this->error(null, $deletion->message, 401);
    }
}
