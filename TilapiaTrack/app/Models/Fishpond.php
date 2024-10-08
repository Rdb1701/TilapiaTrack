<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fishpond extends Model
{
    /** @use HasFactory<\Database\Factories\FishpondFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'size',
        'location'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNameWithOwnerAttribute()
    {
        return $this->name . ' - ' . $this->user->name; 
    }
}
