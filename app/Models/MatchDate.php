<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'date_time',
        'location',
        'status',
        'notes',
    ];

    public function matchRecord()
    {
        return $this->belongsTo(MatchRecord::class, 'match_id');
    }
}
