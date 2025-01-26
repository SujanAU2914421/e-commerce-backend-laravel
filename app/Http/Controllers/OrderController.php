<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'orderAddress.email' => 'nullable|email',
            'orderAddress.firstName' => 'nullable|string',
            'orderAddress.lastName' => 'nullable|string',
            'orderAddress.streetAddress' => 'nullable|string',
            'orderAddress.city' => 'nullable|string',
            'orderAddress.state' => 'nullable|string',
            'orderAddress.zip' => 'nullable|string',
            'orderAddress.phone' => 'nullable|string',
            'orderAddress.orderNotes' => 'nullable|string',
            'billingAddress.email' => 'nullable|email',
            'billingAddress.firstName' => 'nullable|string',
            'billingAddress.lastName' => 'nullable|string',
            'billingAddress.streetAddress' => 'nullable|string',
            'billingAddress.city' => 'nullable|string',
            'billingAddress.state' => 'nullable|string',
            'billingAddress.zip' => 'nullable|string',
            'billingAddress.phone' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_time_of_addition' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'customer_id' => Auth::id(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'currency' => "$",
            'shipping_cost' => 0,
            'order_email' => $validatedData['orderAddress']['email'] ?? "",
            'order_first_name' => $validatedData['orderAddress']['firstName'] ?? "",
            'order_last_name' => $validatedData['orderAddress']['lastName'] ?? "",
            'order_street_address' => $validatedData['orderAddress']['streetAddress'] ?? "",
            'order_city' => $validatedData['orderAddress']['city'] ?? "",
            'order_state' => $validatedData['orderAddress']['state'] ?? "",
            'order_zip' => $validatedData['orderAddress']['zip'] ?? "",
            'order_phone' => $validatedData['orderAddress']['phone'] ?? "",
            'order_notes' => $validatedData['orderAddress']['orderNotes'] ?? "",
            'billing_email' => $validatedData['billingAddress']['email'] ?? "",
            'billing_first_name' => $validatedData['billingAddress']['firstName'] ?? "",
            'billing_last_name' => $validatedData['billingAddress']['lastName'] ?? "",
            'billing_street_address' => $validatedData['billingAddress']['streetAddress'] ?? "",
            'billing_city' => $validatedData['billingAddress']['city'] ?? "",
            'billing_state' => $validatedData['billingAddress']['state'] ?? "",
            'billing_zip' => $validatedData['billingAddress']['zip'] ?? "",
            'billing_phone' => $validatedData['billingAddress']['phone'] ?? "",
        ]);

        foreach ($validatedData['items'] as $item) {
            $cartItem = Cart::where('customer_id', Auth::id())
                ->where('product_id', $item['product_id'])
                ->first();

            if ($cartItem) {
                $priceAtTimeOfAddition = $cartItem->price_at_time_of_addition;
                $discountAtTimeOfAddition = $cartItem->discount_at_time_of_addition;

                $priceAfterDiscount = $priceAtTimeOfAddition - (($discountAtTimeOfAddition / 100) * $priceAtTimeOfAddition);
                $priceAfterDiscount = max($priceAfterDiscount, 0);

                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $priceAtTimeOfAddition,
                    'discount' => $discountAtTimeOfAddition,
                    'total_price' => $item['quantity'] * $priceAfterDiscount,
                ]);
            } else {
                return response()->json([
                    'message' => "Cart item not found for product ID: {$item['product_id']}",
                ], 404);
            }
        }

        Cart::where('customer_id', Auth::id())
            ->whereIn('product_id', array_column($validatedData['items'], 'product_id'))
            ->delete();

        $orderWithItems = $order->load('orderItems');
        return response()->json([
            'message' => 'Order created successfully.',
            'order' => $orderWithItems,
        ], 201);
    }


    public function fetchAllOrders()
    {
        $orders = Order::with(['orderItems', 'orderItems.product', 'orderItems.product.colors'])
            ->orderBy('created_at', 'desc')->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Order not found.',
                'data' => null,
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'message' => 'Order fetched successfully.',
            'data' => $orders,
            'status' => 200,
        ], 200);
    }


    public function fetchOneOrder(Request $request)
    {
        if ($request->has('id')) {
            $order = Order::with(['orderItems', 'orderItems.product', 'orderItems.product.colors'])
                ->where('id', $request->id)->first();
            return response()->json([
                'message' => $order ? 'Order fetched successfully.' : 'Order not found.',
                'data' => $order ?? null,
                'status' => 200,
            ], 200);
        } else {
            $orders = Order::with(['orderItems', 'orderItems.product'])
                ->orderBy('created_at', 'desc')->get();

            return response()->json([
                'message' => $orders->isEmpty() ? 'Orders not found.' : 'Orders fetched successfully.',
                'data' => $orders->isEmpty() ? null : $orders,
                'status' => 200,
            ], 200);
        }
    }

    public function update(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'orderAddress' => 'nullable|array',
            'billingAddress' => 'nullable|array',
        ]);

        $order->update([
            'status' => $validatedData['status'] ?? $order->status,
            'payment_status' => $validatedData['payment_status'] ?? $order->payment_status,
        ]);

        if (isset($validatedData['orderAddress'])) {
            $order->update([
                'order_email' => $validatedData['orderAddress']['email'] ?? $order->order_email,
                'order_first_name' => $validatedData['orderAddress']['firstName'] ?? $order->order_first_name,
                'order_last_name' => $validatedData['orderAddress']['lastName'] ?? $order->order_last_name,
                'order_street_address' => $validatedData['orderAddress']['streetAddress'] ?? $order->order_street_address,
                'order_city' => $validatedData['orderAddress']['city'] ?? $order->order_city,
                'order_state' => $validatedData['orderAddress']['state'] ?? $order->order_state,
                'order_zip' => $validatedData['orderAddress']['zip'] ?? $order->order_zip,
                'order_phone' => $validatedData['orderAddress']['phone'] ?? $order->order_phone,
            ]);
        }

        if (isset($validatedData['billingAddress'])) {
            $order->update([
                'billing_email' => $validatedData['billingAddress']['email'] ?? $order->billing_email,
                'billing_first_name' => $validatedData['billingAddress']['firstName'] ?? $order->billing_first_name,
                'billing_last_name' => $validatedData['billingAddress']['lastName'] ?? $order->billing_last_name,
                'billing_street_address' => $validatedData['billingAddress']['streetAddress'] ?? $order->billing_street_address,
                'billing_city' => $validatedData['billingAddress']['city'] ?? $order->billing_city,
                'billing_state' => $validatedData['billingAddress']['state'] ?? $order->billing_state,
                'billing_zip' => $validatedData['billingAddress']['zip'] ?? $order->billing_zip,
                'billing_phone' => $validatedData['billingAddress']['phone'] ?? $order->billing_phone,
            ]);
        }

        return response()->json(['message' => 'Order updated successfully.', 'order' => $order], 200);
    }

    public function cancel(Request $request)
    {
        $order = Order::find($request->id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order status updated to cancelled successfully'], 200);
    }
}
