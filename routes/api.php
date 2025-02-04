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

// Additional routes for import and export
Route::post('products/import', [ProductController::class, 'import']);
Route::get('products/export', [ProductController::class, 'export']);
