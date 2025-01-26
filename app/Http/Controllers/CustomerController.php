<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer; // Changed to Customer model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomerController extends Controller // Changed to CustomerController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email', // Changed to customers table
            'password' => 'required',
        ]);

        DB::beginTransaction();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validated = $validator->validated();

            $customer = Customer::where('email', $validated['email'])->first(); // Changed to Customer model

            if (Auth::guard('customer')->attempt($request->only('email', 'password'), true)) {
                DB::commit();

                return response()->json([
                    'message' => 'Login successful!',
                    'customer' => $customer, // Changed to Auth::user() for customer
                    'token' => $customer->createToken('authToken')->plainTextToken, // Changed to customer
                    'status' => 200,
                ]);
            } else {
                throw new Exception("Authentication failed.");
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email', // Changed to customers table
            'password' => 'required|min:6',
        ], [
            'username.required' => 'The username field is mandatory.',
            'email.required' => 'Please enter a valid email address.',
            'password.required' => 'Password is required and must be at least 6 characters.',
        ]);

        DB::beginTransaction();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validated = $validator->validated();

            $customer = Customer::create([ // Changed to Customer model
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);


            if (Auth::guard('customer')->attempt($request->only('email', 'password'), true)) {
                DB::commit();

                return response()->json([
                    'message' => 'Signup successful!',
                    'customer' => $customer, // Changed to Auth::user() for customer
                    'token' => $customer->createToken('authToken')->plainTextToken, // Changed to customer
                    'status' => 200,
                ]);
            } else {
                throw new Exception("Authentication failed.");
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function checkAuthenticated(Request $request)
    {
        if ($request->bearerToken()) {
            if ($request->user()) {
                return response()->json([
                    'user' => $request->user(), // Changed to customer
                    'message' => 'Customer is authenticated', // Changed to customer
                ], 200);
            } else {
                return response()->json([
                    'user' => null, // Changed to customer
                    'token' => $request->bearerToken(),
                    'message' => 'Customer is not authenticated', // Changed to customer
                ], 200);
            }
        }
        return response()->json([
            "token" => $request->bearerToken(),
            'message' => 'token not provided',
        ], 200);
    }

    public function getOrder(Request $request)
    {
        $customer = $request->user();
        $orders = $customer->orders;
        return response()->json([
            'orders' => $orders,
        ], 200);
    }

    public function logout(Request $request)
    {
        if ($request->bearerToken()) {

            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                "status" => 200,
                'message' => 'Customer logged out successfully', // Changed to customer
            ], 200);
        }

        return response()->json([
            'status' => 400,
            'message' => 'No token provided',
        ], 400);
    }
}
