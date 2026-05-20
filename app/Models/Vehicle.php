<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate_number',
        'type',
        'capacity',
        'status',
        'last_maintained',
    ];

    protected $casts = [
        'last_maintained' => 'datetime',
    ];

    // Relationships
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
