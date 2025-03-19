<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'payload'
    ];
    
    protected function casts(): array {
        return [
            'payload' => 'array'
        ];
    }
        
}
