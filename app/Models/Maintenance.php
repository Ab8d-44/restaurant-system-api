<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{

    use HasFactory;

    protected $fillable = [
        'maintenance_item',
        'total_price',
        'price',
        'discount',
        'notes',
        'employee_id'
    ];

    public function employee(){

        return $this->belongsTo(Employee::class);

    }

}
