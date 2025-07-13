<?php

namespace App\Http\Controllers\Api\TreatmentPlan;

use Illuminate\Http\Request;
use App\Models\TreatmentPlan;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\PatientResource;
use App\Services\TreatmentPlan\TreatmentPlanService;
use App\Http\Resources\TreatmentPlan\TreatmentPlanResource;
use App\Http\Requests\TreatmentPlan\StorTreatmentPlanRequest;
use App\Http\Requests\TreatmentPlan\UpdateTreatmentPlanRequest;


class TreatmentPlanController extends Controller
{


    public function __construct(private TreatmentPlanService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorTreatmentPlanRequest $request)
    {
        $result = $this->service->store($request);

        if (!$result->success) {
            return response()->json([
                'message' => $result->message,
            ], 403);
        }

        return response()->json([
            'message' => $result->message,
            'data'    => new TreatmentPlanResource($result->data),
        ], 201);
    }





    public function LinkedPatient()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $doctor = $user->doctor;
        if (!$doctor) {
            return response()->json(['message' => 'الطبيب غير موجود'], 403);
        }

        return PatientResource::collection($doctor->patients);

        // return response()->json($patients);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateTreatmentPlanRequest $request, TreatmentPlan $TreatmentPlan)
    {

        // $this->authorize('update', $TreatmentPlan);`

        $TreatmentPlan = $this->service->update($TreatmentPlan, $request->validated());
        if ($TreatmentPlan->success) {
            return $this->success([
                'message' => 'Treatment Plan update successfully',
                'data' => new TreatmentPlanResource($TreatmentPlan->data)
            ]);
        } else {
            return $this->error(
                null,
                $TreatmentPlan->message,
                401
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
