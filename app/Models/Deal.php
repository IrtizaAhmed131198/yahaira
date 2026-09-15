<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'lead_id',
        'assigned_setter_id',
        'status',
        'consultation_at',
        'zoom_link',
        'notes',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function setter()
    {
        return $this->belongsTo(User::class, 'assigned_setter_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
