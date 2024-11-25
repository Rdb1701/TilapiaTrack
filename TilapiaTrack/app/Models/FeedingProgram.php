<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedingProgram extends Model
{
    /** @use HasFactory<\Database\Factories\FeedingProgramFactory> */
    use HasFactory;


    protected $fillable = [
        'feed_id',
        'fish_size',
        'name',
        'feed_time',
        'description',
        'duration'
    ];


    public function feed():BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }


    protected $casts = [
        'feed_time' => 'array',
    ];

    public function setFeedTimeAttribute($value)
    {
        // Check if the value is an array
        if (is_array($value)) {
            // Process each time entry and format it as 'H:i:s' with seconds set to '01'
            $formattedTimes = array_map(function ($time) {
                return substr($time, 0, 5) . ':01'; // Appending ':01' for seconds
            }, $value);

            // Store the formatted times as JSON
            $this->attributes['feed_time'] = json_encode($formattedTimes);
        } else {
            // Handle single time input, ensuring it has seconds set to '01'
            $this->attributes['feed_time'] = json_encode([substr($value, 0, 5) . ':01']);
        }
    }



}
