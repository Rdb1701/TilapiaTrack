<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeedingSchedule;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class SendFeedingNotifications extends Command
{
    protected $signature = 'notifications:send-feeding';

    protected $description = 'Send feeding schedule notifications to users based on their schedules';

    public function handle()
    {
        $now = Carbon::now();
        $currentDay = $now->format('l');
        $currentTime = $now->format('H:i:s');

        $schedules = FeedingSchedule::where('feed_time', $currentTime)
            ->whereJsonContains('days_of_week', $currentDay)
            ->with('fingerling.fishpond.user')
            ->get();
        
            // $schedules = FeedingSchedule::with('fingerling.fishpond.user')
            // ->get();

        foreach ($schedules as $schedule) {
            $user = $schedule->fingerling->fishpond->user;

            if ($user) {
                Notification::make()
                    ->title('Feeding Time Reminder!')
                    ->body("Hello {$user->name}, it's time to feed your fish in {$schedule->fingerling->fishpond->name}!")
                    ->icon('heroicon-o-bell')
                    ->iconColor('success')
                    ->broadcast($user)
                    ->sendToDatabase($user);

                $this->info("Sent notification to {$user->name} for fishpond {$schedule->fingerling->fishpond->name}");
            }
        }

        $this->info("Feeding notifications check completed at {$now->toDateTimeString()}");

        return 0;
    }
}