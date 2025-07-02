<?php

namespace App\Services\Notification;

use Exception;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\VitalSign;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;
use App\Notifications\VitalSignAlertNotification;

class NotificationService
{
   
    public function sendAlertToDoctors(int $patientId, VitalSign $vital): ServiceResult
    {
        $patient = Patient::find($patientId);
        if (!$patient) {
            return new ServiceResult(false, null, "Patient not found.");
        }

        $message = "🚨 Attention: vital signs of the patient{$patient->name}  Abnormal:\n"
            . "temperature: {$vital->temperature}°C\n"
            . "blood pressure {$vital->blood_pressure_systolic}/{$vital->blood_pressure_diastolic}\n"
            . "heart rate: {$vital->heart_rate}\n"
            . "respiratory rate: {$vital->respiratory_rate}\n"
            . "measured_at : {$vital->measured_at}";

        try {
            foreach ($patient->doctors as $doctor) {
                Log::info('Patient name: ' . $patient->user->name);

                $doctor->notify(new VitalSignAlertNotification($message, $patient->user->name));
            }
            return new ServiceResult(true, null, "Notifications sent successfully.");
        } catch (\Exception $e) {
            return new ServiceResult(false, null, "Failed to send notifications: " . $e->getMessage());
        }
    }

    public function listNotifications(Doctor $doctor, string $filter = 'unread', int $perPage = 10): ServiceResult
    {
        try {
            if ($filter === 'read') {
                $notifications = $doctor->readNotifications()->paginate($perPage);
            } elseif ($filter === 'all') {
                $notifications = $doctor->notifications()->paginate($perPage);
            } else {
                $notifications = $doctor->unreadNotifications()->paginate($perPage);
            }

            return new ServiceResult(true, $notifications);
        } catch (Exception $e) {
            Log::error('Failed to list notifications: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Failed to fetch notifications');
        }
    }

    public function markAsRead(Doctor $doctor, string $notificationId): ServiceResult
    {
        try {
            $notification = $doctor->notifications()->where('id', $notificationId)->first();

            if (!$notification) {
                return new ServiceResult(false, null, 'Notification not found');
            }

            $notification->markAsRead();
            return new ServiceResult(true, null, 'Notification marked as read');
        } catch (Exception $e) {
            Log::error('Failed to mark notification as read: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Failed to mark notification as read');
        }
    }

    public function deleteNotification(Doctor $doctor, string $notificationId): ServiceResult
    {
        try {
            $notification = $doctor->notifications()->where('id', $notificationId)->first();

            if (!$notification) {
                return new ServiceResult(false, null, 'Notification not found');
            }

            $notification->delete();
            return new ServiceResult(true, null, 'Notification deleted successfully');
        } catch (Exception $e) {
            Log::error('Failed to delete notification: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Failed to delete notification');
        }
    }
}
