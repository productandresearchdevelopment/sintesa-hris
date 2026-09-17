<?php

namespace App\Libraries;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notifier
{
    public static function send(int|string $userId, string $title, ?string $module = null, ?string $message = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'module'  => $module,
            'message' => $message,
            'is_read' => false
        ]);
    }

    public static function sendToMany(array $userIds, string $title, ?string $module = null, ?string $message = null)
    {
        foreach ($userIds as $userId) {
            self::send($userId, $title, $module, $message);
        }
    }

    public static function markAsRead(int|string $id)
    {
        Notification::where('id', $id)->update(['is_read' => true]);
    }

    public static function markAllAsReadByModule(int|string|null $userId = null, ?string $module = null)
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

    public static function unreadCount(int|string|null $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->pluck('total', 'module');
    }
}
