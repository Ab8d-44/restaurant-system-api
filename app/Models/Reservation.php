<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{

    use HasFactory;

    protected $fillable = [
        'reservation_code',
        'party_size',
        'status',
        'date',
        'special_requests',
        'customer_id',
        'employee_id'
    ];

    public function customer(){

        return $this->belongsTo(Customer::class);

    }

    public function employee(){

        return $this->belongsTo(Employee::class);

    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tables() {
        return $this->belongsToMany(Table::class)        
        ->withPivot('start_time' , 'end_time')
        ->withTimestamps();
    }

}
