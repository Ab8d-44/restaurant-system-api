<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{

    use HasFactory;

    protected $fillable = [
        'table_number',
        'capacity',
        'location',
        'is_active',
        'price_per_hour',
        'description'
    ];

    public function reservations() {
        return $this->belongsToMany(Reservation::class)        
        ->withPivot('start_time' , 'end_time')
        ->withTimestamps();
    }

}
