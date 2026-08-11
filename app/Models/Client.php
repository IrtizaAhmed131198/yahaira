<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'deal_id',
        'full_name',
        'email',
        'phone',
        'timezone',
        'status',
        'date_of_birth',
        'occupation',
        'relationship_goal',
        'commitment_timeline',
        'core_values',
        'lifestyle',
        'faith',
        'children',
        'deal_breakers',
        'current_stage',
        'learned_from_last_relationship',
        'ready_for_now',
        'support_system',
        'partner_age_range',
        'partner_location_radius',
        'partner_education_level',
        'partner_career_stage',
        'partner_must_haves',
        'partner_nice_to_haves',
        'reviewed_by',
        'review_notes',
        'application_status'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function photos()
    {
        return $this->hasMany(ClientPhoto::class);
    }
}
