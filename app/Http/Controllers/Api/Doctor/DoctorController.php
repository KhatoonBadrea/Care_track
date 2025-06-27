<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Doctor\DoctorService;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;

class DoctorController extends Controller
{

    public function __construct(private DoctorService $doctorService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        return DoctorResource::collection($doctors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {


        $doctor = $this->doctorService->create($request->validated());
        if ($doctor->success) {
            return $this->success([
                'message' => 'doctor created successfully',
                'data' => new DoctorResource($doctor->data)
            ]);
        } else {
            return $this->error(
                null,
                $doctor->message,
                401
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $result = $doctor->load('patients');
        return $this->success(new doctorResource($result));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {


        $doctor = $this->doctorService->update($doctor, $request->validated());
        // return new doctorResource($doctor);
        if ($doctor->success) {
            return $this->success([
                'message' => 'doctor update successfully',
                'data' => new DoctorResource($doctor->data)
            ]);
        } else {
            return $this->error(
                null,
                $doctor->message,
                401
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {


        $deletion = $this->doctorService->delete($doctor);
        return $deletion->success
            ? $this->success([
                'message' => 'doctor deleted successfully',
                'data' => new DoctorResource($deletion->data)
            ])
            : $this->error(null, $deletion->message, 401);
    }

    //test
}
