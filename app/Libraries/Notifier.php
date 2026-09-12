<?php

namespace App\Libraries;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notifier
{
    public static function send($userId, $title, $module = null, $message = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'module'  => $module,
            'message' => $message,
            'is_read' => false
        ]);
    }

    public static function sendToMany($userIds, $title, $module = null, $message = null)
    {
        foreach ($userIds as $userId) {
            self::send($userId, $title, $module, $message, false);
        }
    }

    public static function markAsRead($id)
    {
        Notification::where('id', $id)->update(['is_read' => true]);
    }

    public static function markAllAsReadByModule($userId = null, $module = null)
    {
        $userId = $userId ?? Auth::id();
        $moduleMap = [
            'helpdesk' => ['helpdesk', 'helpdesk_answer'],
        ];

        $modules = $moduleMap[$module] ?? [$module];

        Notification::where('user_id', $userId)
            ->whereIn('module', $modules)
            ->update(['is_read' => true]);
    }


    public static function unreadCount($userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->pluck('total', 'module');
    }
}
