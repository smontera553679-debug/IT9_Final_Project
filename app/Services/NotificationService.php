<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send a notification to all customers (role = 'customer').
     */
    public static function notifyAllCustomers(
        string $title,
        string $message,
        string $type = 'info',
        ?string $link = null
    ): void {
        $customerIds = User::where('role', 'customer')->pluck('id');

        if ($customerIds->isEmpty()) return;

        $now = now();

        $rows = $customerIds->map(fn($uid) => [
            'user_id'    => $uid,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'link'       => $link,
            'is_read'    => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        Notification::insert($rows);
    }
}