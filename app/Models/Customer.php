<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{

    use HasFactory;

    protected $fillable = [
        'allergies',
        'favorite_categories',
        'ban',
        'ban_date',
        'user_id'
    ];

    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function orders()
    {

        return $this->hasMany(Order::class);

    }

    public function reservations()
    {

        return $this->hasMany(Reservation::class);

    }

    public function offer_rating()
    {
        return $this->hasOne(OfferRating::class);
    }
    public function meal_rating()
    {
        return $this->hasOne(MealRating::class);
    }
    public function isBanned(): bool
    {
        return $this->ban && (
            !$this->ban_date ||
            $this->ban_date->gt(now()->subDays(7))
        );
    }
    public function ban(): void
    {
        $this->update([
            'ban' => true,
            'ban_date' => now()
        ]);
    }
    public function unban(): void
    {
        $this->update([
            'ban' => false,
            'ban_date' => null
        ]);
    }
    public static function liftExpiredBans(): void
    {
        static::where('ban', true)
            ->where('ban_date', '<=', now()->subDays(7))
            ->update([
                'ban' => false,
                'ban_date' => null
            ]);
    }
    public function cancelledReservationsCount()
    {
        return $this->reservations()
            ->where('status', 'Cancelled')
            ->count();
    }

    public function checkAndBanForCancellations()
    {
        if ($this->cancelledReservationsCount() >= 3) {
            $this->ban();
            return true;
        }
        return false;
    }
}
