<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\FinishController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('structures', StructureController::class);
Route::apiResource('sizes', SizeController::class);
Route::apiResource('product-categories', ProductCategoryController::class);
Route::apiResource('finishes', FinishController::class);
Route::apiResource('designs', DesignController::class);
Route::apiResource('colors', ColorController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('users', UserController::class);

// Additional routes for import and export
Route::post('products/import', [ProductController::class, 'import']);
Route::get('products/export', [ProductController::class, 'export']);

// Authentication routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
