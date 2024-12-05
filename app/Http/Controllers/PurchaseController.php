<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Exception;

class PurchaseController extends Controller
{
    public function index()
    {
        try {
            $purchases = Purchase::with('supplier')->get();
            return response()->json($purchases, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch purchases', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $purchase = Purchase::with('supplier')->findOrFail($id);
            return response()->json($purchase, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Purchase not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'ref_no' => 'required|string|unique:purchases,ref_no',
                'total' => 'required|numeric|min:0',
                'paid' => 'required|numeric|min:0',
                'balance' => 'required|numeric|min:0',
                'date' => 'required|date',
            ]);

            $purchase = Purchase::create($validated);
            return response()->json(['message' => 'Purchase created successfully', 'purchase' => $purchase], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create purchase', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            $validated = $request->validate([
                'supplier_id' => 'exists:suppliers,id',
                'ref_no' => 'string|unique:purchases,ref_no,' . $purchase->id,
                'total' => 'numeric|min:0',
                'paid' => 'numeric|min:0',
                'balance' => 'numeric|min:0',
                'date' => 'date',
            ]);

            $purchase->update($validated);
            return response()->json(['message' => 'Purchase updated successfully', 'purchase' => $purchase], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update purchase', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            $purchase->delete();
            return response()->json(['message' => 'Purchase deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete purchase', 'error' => $e->getMessage()], 500);
        }
    }
}