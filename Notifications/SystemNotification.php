<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * SystemNotification handles internal database notifications for both users and admins.
 * Payload can contain: title, message, action_url, icon, and color.
 */
class SystemNotification extends Notification
{
    use Queueable;

    public function __construct(private array $payload = [])
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->payload['title'] ?? 'Notification',
            'message' => $this->payload['message'] ?? '',
            'action_url' => $this->payload['action_url'] ?? null,
            'icon' => $this->payload['icon'] ?? 'bi-bell',
            'color' => $this->payload['color'] ?? 'primary',
        ];
    }
}
