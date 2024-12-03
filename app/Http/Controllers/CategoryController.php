<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch categories', 'error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        } catch (Exception $e) {
            return response()->json(['message' => 'Category not found', 'error' => $e->getMessage()]);
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
            return response()->json(['message' => 'Category created successfully', 'category' => $category]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create category', 'error' => $e->getMessage()]);
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
            return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update category', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete category', 'error' => $e->getMessage()]);
        }
    }
}