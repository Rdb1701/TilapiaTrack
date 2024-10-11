<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class FishFeedReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        // You can pass any data you want here
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Include 'broadcast'
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Hello ' . $notifiable->name . ', it\'s time to feed your fishes!',
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Hello ' . $notifiable->name . ', it\'s time to feed your fishes!',
        ];
    }
}
