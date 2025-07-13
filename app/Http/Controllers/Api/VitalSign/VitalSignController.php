<?php

namespace App\Http\Controllers\Api\VitalSign;

use App\Models\VitalSign;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\VitalSign\VitalSignService;
use App\Http\Resources\VitalSign\VitalSignResource;
use App\Http\Requests\VitalSign\StoreVitalSignRequest;
use App\Http\Requests\VitalSign\UpdateVitalSignRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\VitalSign\VitalSignAverageResource;

class VitalSignController extends Controller
{
    use AuthorizesRequests;


    public function __construct(private VitalSignService $vitalSignService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        Log::info('ENTERED POLICY VIEW', [
            'user_id' => $user->id,
            // 'patient_id' => $vitalSign->patient_id,

        ]);
        $result = $this->vitalSignService->index($request->all(), $user);

        if (!$result->success) {
            return $this->error(null, $result->message);
        }

        return $this->paginated(
            $result->data,
            VitalSignAverageResource::class,
            'Averages retrieved successfully.'
        );
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVitalSignRequest $request)
    {


        $vitalSign = $this->vitalSignService->create($request->validated());

        if ($vitalSign->success) {
            return $this->success([
                'message' => 'vitalSign created successfully',
                'data' => new VitalSignResource($vitalSign->data)
            ]);
        } else {
            return $this->error(
                null,
                $vitalSign->message,
                401
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VitalSign $vitalSign)
    {
        $this->authorize('view', $vitalSign);

        return $this->success(
            new VitalSignResource($vitalSign),
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVitalSignRequest $request, VitalSign $vitalSign)
    {

        $this->authorize('update', $vitalSign);

        $vitalSign = $this->vitalSignService->update($vitalSign, $request->validated());
        if ($vitalSign->success) {
            return $this->success([
                'message' => 'vitalSign update successfully',
                'data' => new vitalSignResource($vitalSign->data)
            ]);
        } else {
            return $this->error(
                null,
                $vitalSign->message,
                401
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VitalSign $vitalSign)
    {

        $this->authorize('delete', $vitalSign);

        $deletion = $this->vitalSignService->delete($vitalSign);
        return $deletion->success
            ? $this->success([
                'message' => 'vitalSign deleted successfully',
                'data' => new VitalSignResource($deletion->data)
            ])
            : $this->error(null, $deletion->message, 401);
    }
}
