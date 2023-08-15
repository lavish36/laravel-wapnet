<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Cart-related routes
    Route::get('/carts', [CartController::class, 'index']);
    Route::post('/carts', [CartController::class, 'store']);
    Route::get('/carts/{cart}', [CartController::class, 'show']);
  
    // Cart item routes
    Route::post('/carts/{cart}/items', [CartItemController::class, 'store']);
    Route::put('/carts/{cart}/items/{item}', [CartItemController::class, 'update']);
    Route::delete('/carts/{cart}/items/{item}', [CartItemController::class, 'destroy']);

    // Checkout route
    Route::post('/carts/{cart}/checkout', [CartController::class, 'checkout']);
});

// Authentication routes (Breeze-generated routes)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// CSRF route (no need to change, Laravel handles this automatically)
Route::post('/csrf', function() {
    return response()->json(['csrf_token' => csrf_token()]);
});
