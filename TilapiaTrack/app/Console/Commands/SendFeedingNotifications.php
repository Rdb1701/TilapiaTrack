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
        $currentTime = $now->format('H:i:s'); // Get current time in HH:mm format

        // Query all FeedingSchedules where the feeding_program's feed_time matches the current time
        $schedules = FeedingSchedule::whereHas('feedingProgram', function ($query) use ($currentTime) {
            $query->whereJsonContains('feed_time', $currentTime);
        })
        ->with('fingerling.fishpond.user') // Load user related to fingerling's fishpond
        ->get();
        
        // Process each schedule
        foreach ($schedules as $schedule) {
            $user = $schedule->fingerling->fishpond->user;

            if ($user) {
                // Send notification to user
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
