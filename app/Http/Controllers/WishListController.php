<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    /**
     * Store a product in the user's wishlist.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the product already exists in the wishlist
        $existingWishList = WishList::where('customer_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingWishList) {
            return response()->json(['message' => 'Product is already in your wishlist.'], 400);
        }

        // Create the wishlist item
        $wishList = WishList::create([
            'customer_id' => $user->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message' => 'Product added to wishlist successfully.',
            'data' => $wishList,
        ], 201);
    }


    /**
     * Fetch all products in the user's wishlist.
     */
    public function fetch()
    {
        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch wishlist items with related product details
        $wishListItems = WishList::where('customer_id', $user->id)
            ->with([
                'product',
                'product.comments', // Eager load comments relationship on product
                'product.colors', // Eager load colors relationship on product
                'product.category', // Eager load category relationship on product
            ])
            ->get();


        return response()->json([
            'message' => 'Wishlist fetched successfully.',
            'data' => $wishListItems,
        ], 200);
    }

    /**
     * Remove a product from the user's wishlist.
     */
    public function delete($productId)
    {
        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the wishlist item
        $wishList = WishList::where('customer_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$wishList) {
            return response()->json(['message' => 'Product not found in your wishlist.'], 404);
        }

        // Delete the wishlist item
        $wishList->delete();

        return response()->json(['message' => 'Product removed from wishlist successfully.'], 200);
    }
}
