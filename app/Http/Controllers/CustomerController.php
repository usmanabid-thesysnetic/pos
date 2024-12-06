<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Exception;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json($customers, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch customers', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json($customer, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Customer not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'phone_number' => 'required|string|unique:customers,phone_number',
                'email' => 'nullable|email',
            ]);

            $customer = Customer::create($validated);
            return response()->json(['message' => 'Customer created successfully', 'customer' => $customer], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create customer', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validated = $request->validate([
                'name' => 'string',
                'phone_number' => 'string|unique:customers,phone_number,' . $customer->id,
                'email' => 'nullable|email',
            ]);

            $customer->update($validated);
            return response()->json(['message' => 'Customer updated successfully', 'customer' => $customer], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update customer', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(['message' => 'Customer deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete customer', 'error' => $e->getMessage()], 500);
        }
    }
}
