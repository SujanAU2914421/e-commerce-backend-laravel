<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Categories fetched successfully',
            'data' => $categories,
        ], 200);
    }
}
