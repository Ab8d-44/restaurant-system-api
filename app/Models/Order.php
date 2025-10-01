<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'notes',
        'status',
        'order_type',
        'dated_at',
        'customer_id',
        'employee_id',
        'reservation_id',
        'location_id',

    ];

    public function customer(){

        return $this->belongsTo(Customer::class);

    }

    public function location(){

        return $this->belongsTo(Location::class);

    }

    public function employee(){

        return $this->belongsTo(Employee::class);

    }
    public function meals() {
        return $this->belongsToMany(Meal::class, 'meal_order')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function offers(){
        return $this->belongsToMany(Offer::class, 'offer_order')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }

public function price(): float
{
    $total = 0;

    foreach ($this->meals as $meal) {
        $total += $meal->price() * $meal->pivot->quantity;
    }

    foreach ($this->offers as $offer) {
        $total += $offer->final_price() * $offer->pivot->quantity;
    }

    if($this->order_type == 'Reservation' && $this->reservation !== null) {
        $tables = $this->reservation->table;
        foreach($tables as $table) {
            $total += $table->price_per_hour;
        }
    }
    
    return $total;
}
    // app/Models/Order.php
public function getDeliveryTimeWithBuffer()
{
    // Check if order is delivery type and has location
    if ($this->order_type !== 'Delivery' || !$this->location) {
        return [
            'success' => false,
            'message' => __('lang.invalid_delivery_order'),
            'data' => null
        ];
    }

    try {
        $deliveryTime = Carbon::parse($this->location->delivery_time);
        $estimatedTime = $deliveryTime->copy()->addMinutes(15);

        return [
            'success' => true,
            'message' => __('lang.delivery_time_calculated'),
            'data' => [
                'original_time' => $deliveryTime->format('H:i'),
                'estimated_time' => $estimatedTime->format('H:i'),
                'timezone' => config('app.timezone')
            ]
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => __('lang.time_calculation_error'),
            'error' => $e->getMessage()
        ];
    }
}
}
