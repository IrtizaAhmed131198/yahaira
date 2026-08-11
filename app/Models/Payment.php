<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'package_id',
        'amount',
        'status',
        'payment_method',
        'paid_at',
        'contract_signed_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'contract_signed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
