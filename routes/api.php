<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CategoryController;

use App\Http\Middleware\EnsureTokenIsValid;

Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(EnsureTokenIsValid::class)->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
    
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::get('/{id}', [CompanyController::class, 'show']);
        Route::post('/', [CompanyController::class, 'store']);
        Route::put('/{id}', [CompanyController::class, 'update']);
        Route::delete('/{id}', [CompanyController::class, 'destroy']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});