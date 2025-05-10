<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VasImportLog extends Model
{
    protected $fillable = [
        'imported_at', 'records_inserted', 'total_revenue'
    ];

    protected $casts = [
        'imported_at' => 'datetime',
    ];
}
