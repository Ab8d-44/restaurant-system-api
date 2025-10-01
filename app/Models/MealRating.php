<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealRating extends Model
{

    use HasFactory;

    protected $table = 'meals_ratings';

    protected $fillable = [
        'number_voting',
        'meal_id',
        'customer_id'
    ];

    public function meal(){

        return $this->belongsTo(Meal::class);

    }

}
