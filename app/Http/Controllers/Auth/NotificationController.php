<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Return the latest 15 notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(15)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a single notification as read (only if it belongs to the user).
     */
    public function markRead($id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark ALL unread notifications as read for the authenticated user.
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Return just the unread count (used to update the bell badge).
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Alias — returns latest 20 notifications as JSON.
     * (Keep this if you have a separate frontend call for it.)
     */
    public function getNotificationsJson()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(20)
            ->get();

        return response()->json($notifications);
    }
}