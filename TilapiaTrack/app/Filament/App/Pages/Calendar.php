<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use App\Models\FeedingProgram;
use App\Models\FeedingSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.app.pages.calendar';

    public function getEvents(): array
    {
        try {
            // Eager load the related feed to reduce database queries
            $feedingPrograms = FeedingSchedule::with('feedingProgram.feed', 'fingerling.fishpond.user')
            ->whereHas('fingerling.fishpond.user', function ($query) {
                $query->where('id', Auth::id());
            })
            ->get();
        
            $events = [];

            foreach ($feedingPrograms as $program) {
                // Log the program details for debugging
                Log::info('Processing Feeding Program', [
                    'id' => $program->feedingProgram->id,
                    'name' => $program->feedingProgram->name,
                    'feed_time' => $program->feedingProgram->feed_time,
                    'feed_name' => $program->feedingProgram->feed ? $program->feedingProgram->feed->name : 'No Feed',
                ]);

                // Handle feed times - could be string or array
                $feedTimes = is_string($program->feedingProgram->feed_time) 
                    ? json_decode($program->feedingProgram->feed_time, true) 
                    : $program->feedingProgram->feed_time;

                // Ensure feed times is an array
                if (!is_array($feedTimes)) {
                    $feedTimes = [$program->feedingProgram->feed_time];
                }

                $startDate = Carbon::now();

                // Determine age range or default to 1 week
                $ageRangeInWeeks = $program->feedingProgram->age_range ? (int)$program->feedingProgram->age_range : 1;

                for ($day = 0; $day < ($ageRangeInWeeks * 7); $day++) {
                    foreach ($feedTimes as $time) {
                        // Ensure time is in a valid format
                        if (!$time) continue;

                        try {
                            $carbonTime = Carbon::createFromFormat('H:i:s', $time);
                            $formattedTime = $carbonTime->format('h:i A');
                            $date = $startDate->copy()->addDays($day);

                            $events[] = [
                                'id' => $program->feedingProgram->id . '-' . $day . '-' . $time,
                                'title' => $program->feedingProgram->name,
                                'start' => $date->format('Y-m-d') . 'T' . $time,
                                'description' => implode(' | ', array_filter([
                                    "Program: {$program->feedingProgram->name}",
                                    "Size: {$program->feedingProgram->fish_size}",
                                    "Protein: {$program->feedingProgram->protein_content} %",
                                ])),
                                'extendedProps' => [
                                    'fish_size' => $program->feedingProgram->fish_size,
                                    'protein_content' => $program->feedingProgram->protein_content,
                                    'feed_name' => $program->feedingProgram->feed ? $program->feedingProgram->feed->name : 'No Feed',
                                    'program_name' => $program->feedingProgram->name,
                                ]
                            ];
                        } catch (\Exception $timeException) {
                            Log::error('Error processing time', [
                                'time' => $time,
                                'error' => $timeException->getMessage()
                            ]);
                        }
                    }
                }
            }

            // Log the number of events generated
            Log::info('Total events generated', ['count' => count($events)]);

            return $events;
        } catch (\Exception $e) {
            Log::error('Error in getEvents', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }
}