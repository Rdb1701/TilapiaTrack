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
        'feed_time',
        'days_of_week'
    ];

    protected $casts = [
        'days_of_week' => 'array', 
    ];

    public function setFeedTimeAttribute($value)
    {
        $this->attributes['feed_time'] = substr($value, 0, 5) . ':01';
    }

    public function fingerling(): BelongsTo
    {
        return $this->belongsTo(Fingerling::class);
    }
}
