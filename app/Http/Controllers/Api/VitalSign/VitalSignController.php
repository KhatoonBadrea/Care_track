<?php

namespace App\Http\Controllers\Api\VitalSign;

use App\Models\VitalSign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\VitalSign\VitalSignService;
use App\Http\Resources\VitalSign\VitalSignResource;
use App\Http\Requests\VitalSign\StoreVitalSignRequest;
use App\Http\Requests\VitalSign\UpdateVitalSignRequest;

class VitalSignController extends Controller
{

    public function __construct(private VitalSignService $vitalSignService) {}

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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVitalSignRequest $request, VitalSign $vitalSign)
    {


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


        $deletion = $this->vitalSignService->delete($vitalSign);
        return $deletion->success
            ? $this->success([
                'message' => 'vitalSign deleted successfully',
                'data' => new VitalSignResource($deletion->data)
            ])
            : $this->error(null, $deletion->message, 401);
    }
}
