<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnreadNotifications()
    {
        $user = Auth::user(); // احصل على المستخدم الحالي (البريدر أو الطبيب)

        $unreadNotifications = $user->unreadNotifications;

        return response()->json([
            'status' => 200,
            'message' => 'Unread notifications retrieved successfully',
            'data' => $unreadNotifications,
        ]);
    }

    public function getAllNotifications()
    {
        $user = Auth::guard('breeder')->user() ?? Auth::guard('veterinarian')->user();
        // احصل على المستخدم الحالي

        $notifications = $user->notifications;
        $communityNotifications = $notifications->where('data.notification_type', 'community');
        $chatNotifications = $notifications->where('data.notification_type', 'chat');

        return response()->json([
            'status' => 200,
            'message' => 'All notifications retrieved successfully',
            'data' => [
            'community_notifications' => $communityNotifications,
            'chat_notifications' => $chatNotifications,

        ]
        ]);
    }

    public function markAsRead($notificationId)
    {

        $user = Auth::guard('breeder')->user() ?? Auth::guard('veterinarian')->user();

        // جلب الإشعار المحدد
        $notification = $user->notifications->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead(); // جعل الإشعار مقروءًا
            return response()->json([
                'status' => 200,
                'message' => 'Notification marked as read successfully',
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'Notification not found',
        ]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user(); // احصل على المستخدم الحالي

        // جعل كل الإشعارات غير المقروءة مقروءة
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 200,
            'message' => 'All unread notifications marked as read successfully',
        ]);
    }



}
