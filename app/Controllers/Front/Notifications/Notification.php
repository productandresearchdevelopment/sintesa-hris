<?php

namespace App\Controllers\Front\Notifications;

use App\Http\Controllers\Controller;
use App\Libraries\Notifier;
use Illuminate\Http\Request;
use App\Models\Notification as Mod;

class Notification extends Controller
{
    public function countAll()
    {
        $userId = auth()->id();
        $notifications = Notifier::unreadCount($userId);
        return response()->json(['data' => $notifications, 'message' => 'Data fetched successfully', 'success' => true], 200);
    }

    public function markAsRead($id)
    {
        $notifications = Notifier::markAsRead($id);
        return response()->json(['data' => $notifications, 'message' => 'Data fetched successfully', 'success' => true], 200);
    }

    public function markAllAsReadByModule(Request $request, $module = null)
    {
        $userId = auth()->id();
        $notifications = Notifier::markAllAsReadByModule($userId, $module);
        return response()->json(['data' => $notifications, 'message' => 'Data fetched successfully', 'success' => true], 200);
    }
}
