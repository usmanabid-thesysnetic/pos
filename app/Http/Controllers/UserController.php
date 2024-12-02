<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid login credentials'], 401);
            }

            $token = $user->createToken('auth_token', ['*'], now()->addHours(2))->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 7200,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong during login', 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong during logout', 'error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            return response()->json(User::all());
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong while fetching users', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong while creating user', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'string|max:255',
                'email' => 'email|unique:users,email,' . $user->id,
                'password' => 'string|min:6',
            ]);

            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            return response()->json(['message' => 'User updated successfully', 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong while updating user', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong while deleting user', 'error' => $e->getMessage()], 500);
        }
    }
}