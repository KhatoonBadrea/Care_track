<?php

namespace App\Http\Controllers\Api\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use App\Services\Notification\NotificationService;
use App\Services\Result\ServiceResult;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return $this->error(null, 'Doctor profile not found.', 404);
        }

        $filter = $request->query('filter', 'unread');
        $serviceResult = $this->notificationService->listNotifications($doctor, $filter, 10);

        if (!$serviceResult->success) {
            return $this->error(null, $serviceResult->message, 500);
        }

        $notifications = NotificationResource::collection($serviceResult->data);

        return $this->success(['data' => $notifications]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $user = $request->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return $this->error(null, 'Doctor profile not found.', 404);
        }

        $serviceResult = $this->notificationService->markAsRead($doctor, $id);

        if (!$serviceResult->success) {
            return $this->error(null, $serviceResult->message, 404);
        }

        return $this->success($serviceResult->message, 200);
    }

    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return $this->error(null, 'Doctor profile not found.', 404);
        }

        $serviceResult = $this->notificationService->deleteNotification($doctor, $id);

        if (!$serviceResult->success) {
            return $this->error(null, $serviceResult->message, 404);
        }

        return $this->success($serviceResult->message, 200);
    }
}
