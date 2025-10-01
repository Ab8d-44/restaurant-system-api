<?php

namespace App\Http\Controllers\Api\CategoriesControllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class ShowCategoryController extends Controller
{
    public function show_category($CategoryId)
    {
        $category = Category::find($CategoryId);
        if (!$category) {
            $Response = [
                'success' => false,
                'message' => 'Undefined category.',
                'data' => null,
                'status' => 404
            ];
            return response()->json($Response, 404);
        }
        try {
            $category->load(['parent', 'children', 'meals']);

            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully.',
                'data' => $category,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving category: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'categories_retrieval_failed.',
                'data' => null,
                'status' => 500
            ], 500);
        }
    }
}
