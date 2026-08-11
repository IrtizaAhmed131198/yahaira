<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchRecord extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'client_id',
        'candidate_name',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function compatibility()
    {
        return $this->hasOne(MatchCompatibility::class, 'match_id');
    }

    public function date()
    {
        return $this->hasOne(MatchDate::class, 'match_id');
    }

    public function feedback()
    {
        return $this->hasOne(MatchFeedback::class, 'match_id');
    }
}
