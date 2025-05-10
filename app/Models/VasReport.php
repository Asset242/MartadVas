<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VasReport extends Model
{
    use HasFactory;
        protected $fillable = [
            'service_id',
            'price_point',
            'product_name',
            'product_id',
            'revenue',
            'count',
            'date',
            'transaction'
        ];
}
