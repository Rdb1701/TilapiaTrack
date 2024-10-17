<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Harvest extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'fingerling_id',
        'harvest_date',
        'total_harvest',
        'image_path'
    ];


    protected $casts = [
        'image_path' => 'array',
    ];


    public function fingerling(): BelongsTo
    {
        return $this->belongsTo(Fingerling::class);
    }

}
