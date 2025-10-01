<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use function PHPUnit\Framework\isEmpty;

class Meal extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'prep_time',
        'is_vegetarian',
        'percentage',
        'description',
        'availability',
        'picture_id',
        'category_id'
    ];

    public function category()
    {

        return $this->belongsTo(Category::class);

    }

    public function picture()
    {

        return $this->belongsTo(Picture::class);

    }

    public function ingredients()
    {

        return $this->belongsToMany(Ingredient::class, 'ingredient_meal')
            ->withPivot('quantity')
            ->withTimestamps();

    }

    public function offers()
    {

        return $this->belongsToMany(Offer::class)
            ->withPivot('quantity')
            ->withTimestamps();

    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'meal_order')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function ratings()
    {

        return $this->hasMany(MealRating::class);

    }


    function prep_cost()
    {
        $prep_cost = 0;

        foreach ($this->ingredients as $ingredient) {
            $prep_cost += $ingredient->unit_cost * $ingredient->pivot->quantity;
        }
        return $prep_cost;
    }

    public function price()
    {
        $prep_cost = $this->prep_cost();

        $profitMargin = ceil(($this->percentage / 100) * $prep_cost);
        $finalPrice = $prep_cost + $profitMargin;

        return $finalPrice;
    }
    public function profit_margine()
    {
        $prep_cost = $this->prep_cost();

        $profitMargin = ceil(($this->percentage / 100) * $prep_cost);
        return $profitMargin;
    }

    public function is_active(): bool
    {
        if ($this->ingredients->isEmpty()) {
            return false;
        }

        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->stock_quantity < $ingredient->pivot->quantity) {
                return false;
            }
        }

        return true;
    }

}
