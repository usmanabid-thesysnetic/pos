<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Exception;

class CompanyController extends Controller
{
    public function index()
    {
        try {
            $companies = Company::all();
            return response()->json($companies);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching companies', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $company = Company::findOrFail($id);
            return response()->json($company);
        } catch (Exception $e) {
            return response()->json(['message' => 'Company not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            $company = Company::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json(['message' => 'Company created successfully', 'company' => $company], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating company', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $company = Company::findOrFail($id);

            $request->validate([
                'name' => 'string|max:255',
                'description' => 'string',
            ]);

            $company->update([
                'name' => $request->name ?? $company->name,
                'description' => $request->description ?? $company->description,
            ]);

            return response()->json(['message' => 'Company updated successfully', 'company' => $company]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating company', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return response()->json(['message' => 'Company deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting company', 'error' => $e->getMessage()], 500);
        }
    }
}
