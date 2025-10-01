<?php

namespace App\Http\Controllers\Api\OffersControllers;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Support\Facades\Log;

class GetAllOffersController extends Controller
{
    public function get_all()
    {
        try {
            $offers = Offer::with(['meals', 'ratings'])->get();
            $offersWithDetails = [];

            foreach ($offers as $offer) {
                $offerData = $offer->toArray();
                $offerData['price'] = $offer->price();
                $offerData['total_price'] = $offer->final_price();
                $offersWithDetails[] = $offerData;
            }

            return response()->json([
                'success' => true,
                'message' => null,
                'data' => $offersWithDetails,
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            Log::error('Offer fetch error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get offers',
                'data' => null,
                'status' => 500
            ], 500);
        }
    }
}