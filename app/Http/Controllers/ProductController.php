<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(): JsonResponse
    {
        $products = Product::with(['comments', 'colors', 'category'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Products with comments and reviews fetched successfully',
            'data' => $products,
        ], 200);
    }
    public function search(Request $request): JsonResponse
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([
                'success' => false,
                'query' => $query,
                'message' => 'Search query is required',
            ], 400);
        }

        $products = Product::with(['comments', 'colors', 'category'])
            ->where('title', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Search results fetched successfully',
            'data' => $products,
        ], 200);
    }
}
