<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fingerling extends Model
{
    /** @use HasFactory<\Database\Factories\FingerlingFactory> */
    use HasFactory;

    protected $fillable = [
        'fishpond_id',
        'species',
        'date_deployed',
        'quantity',
        'weight',
        'feed_amount'
       
    ];

    public function fishpond(): BelongsTo
    {
        return $this->belongsTo(Fishpond::class);
    }
}
