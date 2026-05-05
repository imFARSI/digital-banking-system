<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Mark a single notification as read, then redirect to its action URL if present
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $actionUrl = $notification->data['action_url'] ?? null;
        if (is_string($actionUrl) && $actionUrl !== '') {
            return redirect($actionUrl);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    // Mark every unread notification for the current user as read at once
    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
