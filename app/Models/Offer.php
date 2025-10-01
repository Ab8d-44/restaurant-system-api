<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use function PHPUnit\Framework\isEmpty;

class Offer extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'discount_amount',
        'is_active',
        'start_date',
        'end_date',
    ];

    public function meals(){

        return $this->belongsToMany(Meal::class)
        ->withPivot('quantity')
        ->withTimestamps();

    }

    public function ratings(){

        return $this->hasMany(OfferRating::class);

    }
    public function orders()
{
    return $this->belongsToMany(Order::class, 'offer_order')
                ->withPivot('quantity')
                ->withTimestamps();
}
    function price()  {
        $price = 0;

        foreach ($this->meals as $meal) {
        $price += $meal->price() * $meal->pivot->quantity;
        }
        return $price;
    }
    function final_price()  {
        $price = $this->price();
        $final_price = $price - ($price * $this->discount_amount /100);
        return $final_price;
    }
    function discount_margine() {
        $final_price =$this->final_price();
        $price =$this->price();
        $discount_margin = $price - $final_price;
        return $discount_margin;
    }
public function is_active(): bool
{
    $now = now();

    // if (!($this->start_date <= $now && $this->end_date >= $now)) {
    //     return false;
    // }

    foreach ($this->meals as $meal) {
        if ($meal->ingredients->isEmpty()) {
            return false;
        }

        foreach ($meal->ingredients as $ingredient) {
            $requiredQty = $ingredient->pivot->quantity;
            if ($ingredient->stock_quantity < $requiredQty) {
                return false;
            }
        }
    }

    return true;
}

}

