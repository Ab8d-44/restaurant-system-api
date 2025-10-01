<?php

namespace App\Http\Controllers\Api\OffersControllers;

use App\Http\Controllers\Controller;
use App\Models\Offer;

class GetOneOfferController extends Controller
{
    public function get_one($id)
    {
        $offer = Offer::with(['meals', 'ratings'])->find($id);

        if (!$offer) {
            return response()->json([
                'success' => false,
                'meassage' => 'Offer not found',
                'data' => null,
                'status' => 404
            ], 404);
        }

        $price = $offer->price();
        $final_price = $offer->final_price();

        $payload = [
            $offer,
            'price' => $price,
            'total_price' => $final_price
        ];

        return response()->json([
            'success' => true,
            'meassage' => null,
            'data' => $payload,
            'status' => 200
        ], 200);
    }
}