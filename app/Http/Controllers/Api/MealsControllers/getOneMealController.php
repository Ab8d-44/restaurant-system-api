<?php

namespace App\Http\Controllers\api\MealsControllers;

use App\Http\Controllers\Controller;
use App\Models\Meal;

class GetOneMealController extends Controller
{
    public function get_one_meal($id)
    {
        $meal = Meal::with(['ingredients', 'ratings'])->find($id);

        if (!$meal) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Meal not found',
                'status' => 404
            ], 404);
        }

        $payload = [
            'meal' => $meal,
            'price' => $meal->price(),
            'preperation_cost' => $meal->prep_cost(),
            'profit_margine' => $meal->profit_margine()
        ];

        return response()->json([
            'data' => $payload,
            'success' => true,
            'message' => 'Meal fetched successfully',
            'status' => 200
        ], 200);
    }
}