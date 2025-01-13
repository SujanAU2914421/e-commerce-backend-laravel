<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color' => 'required|string',
            'size' => 'required|string',
        ]);

        $user = Auth::user();

        // Check if the product exists
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // Check if the cart already has the item for this user
        $existingCartItem = Cart::where('customer_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCartItem) {
            // Update the quantity if the item already exists
            $existingCartItem->quantity = $request->quantity;
            $existingCartItem->color = $request->color;
            $existingCartItem->size = $request->size;
            $existingCartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Product quantity updated in your cart.',
                'data' => $existingCartItem,
            ], 200);
        }

        // Create a new cart item if it doesn't exist
        $cartItem = Cart::create([
            'customer_id' => $user->id,
            'product_id' => $request->product_id,
            'color' => $request->color,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'price_at_time_of_addition' => $product->price,
            'discount_at_time_of_addition' => $product->discount ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'data' => $cartItem,
        ], 201);
    }


    public function fetch()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.',
                'data' => null,
            ], 401);
        }

        // Retrieve the cart items for the authenticated user and eager load related data
        $cartItems = Cart::where('customer_id', $user->id)
            ->with([
                'product',
                'product.comments',
                'product.colors',
                'product.category',
            ])
            ->get();

        // Check if the cart is empty and return a specific message
        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
                'data' => [],
            ], 200);  // Respond with 200 OK as it's not an error, just an empty cart
        }

        // If the cart has items, return them with a success message
        return response()->json([
            'message' => 'Cart fetched successfully.',
            'data' => $cartItems,
        ], 200);
    }



    /**
     * Remove a product from the user's cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {

        $user = Auth::user();

        $cartItem = Cart::where('customer_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Product not found in your cart.',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Product removed from cart successfully.',
        ], 200);
    }
}
