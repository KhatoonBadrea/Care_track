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
        $user = JWTAuth::parseToken()->authenticate();

        $result = $this->service->index($user);

        if (!$result->success) {
            return $this->error(null, $result->message);
        }

        return $this->paginated(
            $result->data,
            TreatmentPlanResource::class,
            'Treatment plans retrieved successfully.'
        );
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
    public function show(TreatmentPlan $treatmentPlan)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $result = $this->service->show($user, $treatmentPlan);

        if (!$result->success) {
            return $this->error(null, $result->message, 403);
        }

        return $this->success([
            'message' => 'Treatment plan retrieved successfully.',
            'data' => new TreatmentPlanResource($result->data),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan)
    {
        $result = $this->service->update($treatmentPlan, $request->validated());

        if ($result->success) {
            return $this->success([
                'message' => 'Treatment plan updated successfully.',
                'data' => new TreatmentPlanResource($result->data),
            ]);
        }

        return $this->error(
            null,
            $result->message,
            403
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentPlan $treatmentPlan)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $result = $this->service->delete($user, $treatmentPlan);

        if (!$result->success) {
            return $this->error(null, $result->message, 403);
        }

        return $this->success([
            'message' => $result->message,
        ]);
    }
}
