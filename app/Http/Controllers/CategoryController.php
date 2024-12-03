<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch categories', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Category not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|unique:categories,code',
                'name' => 'required|string|max:255',
            ]);

            $category = Category::create($request->only(['code', 'name']));
            return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create category', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $request->validate([
                'code' => 'string|unique:categories,code,' . $id,
                'name' => 'string|max:255',
            ]);

            $category->update($request->only(['code', 'name']));
            return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update category', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete category', 'error' => $e->getMessage()], 500);
        }
    }
}