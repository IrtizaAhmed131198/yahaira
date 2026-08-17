<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'lead_id',
        'assigned_closer_id',
        'status',
        'consultation_at',
        'zoom_link',
        'notes',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'assigned_closer_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
