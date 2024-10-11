<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedConsumption extends Model
{
    /** @use HasFactory<\Database\Factories\FeedConsumptionFactory> */
    use HasFactory;


    protected $fillable = [
        'fingerling_id',
        'feed_id',
        'quantity',
        'consumption_date'
    ];


    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    public function fingerling(): BelongsTo
    {
        return $this->belongsTo(Fingerling::class);
    }
}
