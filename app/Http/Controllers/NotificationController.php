<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function open(string $notification)
    {
        $notification = Auth::user()->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        $notification->markAsRead();

        $url = data_get($notification->data, 'url');

        if ($url) {
            return redirect()->to($url);
        }

        return redirect()->route('dashboard');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', __('All notifications marked as read.'));
    }
}
