<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerServiceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'charge_amount',
        'count',
        'added_date'
    ];
    
}
