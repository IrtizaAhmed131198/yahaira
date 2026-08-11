<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchFeedback extends Model
{
    use HasFactory;

    protected $table = 'match_feedbacks';

    protected $fillable = [
        'match_id',
        'client_feedback',
        'candidate_feedback',
        'rating',
        'notes',
    ];

    public function matchRecord()
    {
        return $this->belongsTo(MatchRecord::class, 'match_id');
    }
}
