<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Auth::user()->unreadNotifications()->latest()->get();
        $unreadCount = auth()->user()->unreadNotifications->count();
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    public function markNotificationsAsRead(Request $request)
    {
        $user = auth()->user();

        if ($request->has('notification_id')) {
            $notificationId = $request->input('notification_id');
            $notification = $user->unreadNotifications
                ->where('id', $notificationId)
                ->first();

            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            $user->unreadNotifications->markAsRead();
        }

        return response()->json([
            'success' => true,
            'total_unread' => $user->unreadNotifications()->count()
        ]);
    }
}
