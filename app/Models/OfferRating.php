<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferRating extends Model
{
    use HasFactory;

    protected $table = 'offers_ratings';

    protected $fillable = [
        'number_voting',
        'customer_id',
        'offer_id'
    ];

    public function offer(){

        return $this->belongsTo(Offer::class);

    }

}
