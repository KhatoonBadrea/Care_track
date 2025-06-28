<?php

namespace App\Http\Controllers\Api\Relative;

use App\Models\Relative;
use Illuminate\Http\Request;
use App\Services\Relative\RelativeService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\PatientResource;
use App\Http\Resources\Relative\RelativeResource;
use App\Http\Requests\Relative\StoreRelativeRequest;
use App\Http\Requests\Relative\UpdateRelativeRequest;

class RelativeController extends Controller
{

    public function __construct(protected RelativeService $relativeService) {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relatives = Relative::all('name', 'relation', 'phone', 'email');
        return RelativeResource::collection($relatives);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRelativeRequest $request)
    {
        $relative = $this->relativeService->create($request->validated());

        if ($relative->success) {
            return $this->success([
                'message' => 'relative create successfully',
                'data' => new RelativeResource($relative->data)
            ]);
        } else {
            return $this->error(
                null,
                $relative->message,
                401
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $relative = Relative::findOrFail($id);
        $relative->load('patients');

        return $this->success(new RelativeResource($relative));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRelativeRequest $request, Relative $relative): JsonResponse
    {
        $relative = $this->relativeService->update($relative, $request->validated());
        if ($relative->success) {
            return $this->success([
                'message' => 'relative update successfully',
                'data' => new RelativeResource($relative->data)
            ]);
        } else {
            return $this->error(
                null,
                $relative->message,
                401
            );
        }
    }
    public function destroy(Relative $relative)
    {
        $result = $this->relativeService->delete($relative);
        if ($result->success) {
            return $this->success([
                'message' => 'relative delete successfully',
            ]);
        } else {
            return $this->error(
                null,
                $result->message,
                401
            );
        }
    }

    public function showPatientDetails($relativeId)
    {
        $result = $this->relativeService->getPatientDetailsForRelative($relativeId);

        if ($result->success) {
            return self::success(
                PatientResource::collection($result->data),
            );
        }

        return self::error(null, $result->message, 404);
    }
}
