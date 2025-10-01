<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{

    use HasFactory;

    protected $fillable = [
        'country',
        'region',
        'delivery_time',
        'state',
        'city',
        'street'
    ];

    public function users(){

        return $this->hasMany(User::class);

    }

}
