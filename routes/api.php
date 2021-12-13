<?php

use App\Http\Controllers\Api\ApiCustomerController;
use App\Http\Controllers\Api\ApiProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('/customers')->group(function () {
    Route::get('/', [ApiCustomerController::class, 'index']);
    Route::post('/store', [ApiCustomerController::class, 'createCustomer']);
    Route::get('/{id}', [ApiCustomerController::class, 'getDetailCustomer']);
    Route::put('/update/{id}', [ApiCustomerController::class, 'updateCustomer']);
    Route::delete('/delete/{id}', [ApiCustomerController::class, 'deleteCustomer']);
});

Route::prefix('/products')->group(function () {
    Route::get('/', [ApiProductController::class, 'index']);
    Route::post('/store', [ApiProductController::class, 'createProduct']);
    Route::get('/{id}', [ApiProductController::class, 'getDetailProduct']);
    Route::put('/update/{id}', [ApiProductController::class, 'updateProduct']);
    Route::delete('/delete/{id}', [ApiProductController::class, 'deleteProduct']);
});
