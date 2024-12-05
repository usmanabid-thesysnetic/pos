<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Exception;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['category', 'supplier'])->get();
            return response()->json($products, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch products', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with(['category', 'supplier'])->findOrFail($id);
            return response()->json($product, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'code' => 'required|string|unique:products,code',
                'name' => 'required|string',
                'cost' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string',
                'quantity' => 'required|integer|min:0',
                'image' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            $product = Product::create($validated);
            return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'category_id' => 'exists:categories,id',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'code' => 'string|unique:products,code,' . $product->id,
                'name' => 'string',
                'cost' => 'numeric|min:0',
                'price' => 'numeric|min:0',
                'currency' => 'string',
                'quantity' => 'integer|min:0',
                'image' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            $product->update($validated);
            return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete product', 'error' => $e->getMessage()], 500);
        }
    }
}
