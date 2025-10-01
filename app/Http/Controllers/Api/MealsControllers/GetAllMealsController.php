<?php

namespace App\Http\Controllers\Api\MealsControllers;

use App\Http\Controllers\Controller;
use App\Models\Meal;

class GetAllMealsController extends Controller
{
    public function get_all_meals()
    {
        $meals = Meal::with(['ingredients', 'ratings'])->get();

        if ($meals->isEmpty()) {
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => 'There is nothing to display',
                'status' => 200
            ], 200);
        }

        $mealsWithDetails = $meals->map(function ($meal) {
            return array_merge(
                $meal->toArray(),
                [
                    'price' => $meal->price(),
                    'prep_cost' => $meal->prep_cost(),
                    'profit_margine' => $meal->profit_margine()
                ]
            );
        });

        return response()->json([
            'data' => $mealsWithDetails,
            'success' => true,
            'message' => 'Meals fetched successfully',
            'status' => 200
        ], 200);
    }
}