<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{

    use HasFactory;

     protected $fillable = [
        'national_id',
        'position',
        'salary',
        'bonus',
        'notes',
        'hire_date',
        'birth_date',
        'user_id'
    ];

    public function user(){

        return $this->belongsTo(User::class);

    }

    public function reservations(){

        return $this->hasMany(Reservation::class);

    }

    public function orders(){

        return $this->hasMany(Order::class);

    }

}
