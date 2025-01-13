<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function fetch($productId): JsonResponse
    {
        // Find the product
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Get all comments for the product
        $comments = Comment::with('customer')->get();

        return response()->json([
            'success' => true,
            'message' => 'Comments fetched successfully',
            'data' => $comments,
        ], 200);
    }

    public function create(Request $request): JsonResponse
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Find the product
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $user = Auth::user();

        // Create the comment
        $comment = new Comment([
            'customer_id' => $user->id,
            'comment' => $request->comment,
            'product_id' => $product->id,
            'rating' => $request->rating,
        ]);

        $product->comments()->save($comment); // Save the comment for the specific product

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment,
        ], 201);
    }

    public function update(Request $request, $commentId): JsonResponse
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Find the comment by id
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], 404);
        }

        // Update the comment
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ], 200);
    }

    /**
     * Delete a comment.
     *
     * @param  int  $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($commentId): JsonResponse
    {
        // Find the comment by id
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], 404);
        }

        // Delete the comment
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ], 200);
    }
}
