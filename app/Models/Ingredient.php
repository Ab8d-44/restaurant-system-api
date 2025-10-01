<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'stock_quantity',
        'unit_cost',
        'is_active'
    ];

    public function meals(){

        return $this->belongsToMany(Meal::class)
        ->withPivot('quantity')
        ->withTimestamps();

    }

}
