<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleProduct;
use Exception;

class SaleController extends Controller
{
    public function createSale(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'customer_id' => 'required|exists:customers,id',
                'ref_no' => 'required|unique:sales,ref_no',
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.discount' => 'required|numeric|min:0',
                'products.*.quantity' => 'required|integer|min:1',
                'paid' => 'required|numeric|min:0',
            ]);

            $totalPrice = 0;
            $price = 0;

            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                if ($product->quantity < $productData['quantity']) {
                    return response()->json(['message' => 'Insufficient quantity for product ID: ' . $product->id], 422);
                }
                if ($productData['discount'] > $product->cost) {
                    return response()->json(['message' => 'Discount cannot exceed cost for product ID: ' . $product->id], 422);
                }
                $productPrice = ($product->price - $productData['discount']) * $productData['quantity'];
                $totalPrice += $productPrice;
                $price += $product->price * $productData['quantity'];
            }

            $balance = $totalPrice - $validated['paid'];
            $status = $balance > 0 ? 'pending' : 'paid';

            $sale = Sale::create([
                'user_id' => $validated['user_id'],
                'customer_id' => $validated['customer_id'],
                'ref_no' => $validated['ref_no'],
                'price' => $price,
                'total' => $totalPrice,
                'paid' => $validated['paid'],
                'balance' => $balance,
                'status' => $status,
                'date' => now(),
            ]);

            foreach ($validated['products'] as $productData) {
                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'discount' => $productData['discount'],
                    'quantity' => $productData['quantity'],
                ]);

                $product = Product::find($productData['product_id']);
                $product->decrement('quantity', $productData['quantity']);
            }

            return response()->json(['message' => 'Sale created successfully', 'sale' => $sale], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'paid' => 'required|numeric|min:0',
            ]);

            $sale = Sale::findOrFail($id);
            if ($sale->status === 'paid') {
                return response()->json(['message' => 'Sale is already paid'], 422);
            }

            $newPaid = $sale->paid + $validated['paid'];
            if ($newPaid > $sale->total) {
                return response()->json(['message' => 'Paid amount cannot exceed total amount'], 422);
            }

            $sale->paid = $newPaid;
            $sale->balance = $sale->total - $newPaid;
            $sale->status = $sale->balance == 0 ? 'paid' : 'pending';
            $sale->save();

            return response()->json(['message' => 'Payment updated successfully', 'sale' => $sale], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Sale not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllSales()
    {
        try {
            $sales = Sale::with('products.product')->get();
            return response()->json(['sales' => $sales], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
