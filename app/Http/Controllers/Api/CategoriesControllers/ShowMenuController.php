<?php

namespace App\Http\Controllers\Api\CategoriesControllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ShowMenuController extends Controller
{
    public function show_menu()
    {
        $categories = Category::with(['meals'])->get();

        $categories->each(function ($category) {
            $category->meals->each(function ($meal) {
                $meal->price = $meal->price();
                $meal->preparation_cost = $meal->prep_cost();
                $meal->profit_margin = $meal->profit_margine();
            });
        });

        return response()->json([
            'success' => true,
            'message' => 'categories_retrieved_successfully.',
            'data' => $categories,
            'status' => 200
        ]);
    }
}
