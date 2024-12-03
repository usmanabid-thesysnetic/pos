<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Exception;

class SupplierController extends Controller
{
    public function index()
    {
        try {
            $suppliers = Supplier::all();
            return response()->json($suppliers, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch suppliers', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            return response()->json($supplier, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Supplier not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15|unique:suppliers,phone_number',
                'email' => 'nullable|email',
            ]);

            $supplier = Supplier::create($request->only(['name', 'phone_number', 'email']));
            return response()->json(['message' => 'Supplier created successfully', 'supplier' => $supplier], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create supplier', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            $request->validate([
                'name' => 'string|max:255',
                'phone_number' => 'string|max:15|unique:suppliers,phone_number,' . $id,
                'email' => 'nullable|email',
            ]);

            $supplier->update($request->only(['name', 'phone_number', 'email']));
            return response()->json(['message' => 'Supplier updated successfully', 'supplier' => $supplier], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update supplier', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            return response()->json(['message' => 'Supplier deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete supplier', 'error' => $e->getMessage()], 500);
        }
    }
}