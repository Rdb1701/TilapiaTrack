<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class FeedingSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\FeedingScheduleFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'fingerling_id',
        'feeding_program_id',
        'start_date',
        'end_date'
    ];

    // protected $casts = [
    //     'days_of_week' => 'array',
    //     'feed_time' => 'array',

    // ];

    // public function setFeedTimeAttribute($value)
    // {
    //     // Check if the value is an array
    //     if (is_array($value)) {
    //         // Process each time entry and format it as 'H:i:s' with seconds set to '01'
    //         $formattedTimes = array_map(function ($time) {
    //             return substr($time, 0, 5) . ':01'; // Appending ':01' for seconds
    //         }, $value);

    //         // Store the formatted times as JSON
    //         $this->attributes['feed_time'] = json_encode($formattedTimes);
    //     } else {
    //         // Handle single time input, ensuring it has seconds set to '01'
    //         $this->attributes['feed_time'] = json_encode([substr($value, 0, 5) . ':01']);
    //     }
    // }


    public function fingerling(): BelongsTo
    {
        return $this->belongsTo(Fingerling::class);
    }

    public function feedingProgram(): BelongsTo
    {
        return $this->belongsTo(FeedingProgram::class);
    }
}
