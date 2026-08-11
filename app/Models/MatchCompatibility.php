<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchCompatibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'values_score',
        'lifestyle_score',
        'goal_alignment',
        'deal_breaker_check',
        'notes',
    ];

    public function matchRecord()
    {
        return $this->belongsTo(MatchRecord::class, 'match_id');
    }
}
