<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/* PRODUCTS - rotas CRUD dos produtos
|--------------------------------------------------------------------------
*/

Route::get('products', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::post('product', [ProductController::class, 'store']);
Route::put('product/{id}', [ProductController::class, 'update']);
Route::delete('product/{id}', [ProductController::class,'destroy']);

/* USERS - rotas CRUD dos users
|--------------------------------------------------------------------------
*/
Route::get('users', [UserController::class, 'index']);
Route::get('user/{id}', [UserController::class, 'show']);
Route::get('customer/{id}', [UserController::class, 'showCustomer']);
Route::post('user', [UserController::class, 'store']);
Route::post('customer', [UserController::class, 'storeCustomer']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::put('customer/{id}', [UserController::class, 'updateCustomer']);
Route::delete('user/{id}', [UserController::class,'destroy']);
Route::delete('customer/{id}', [UserController::class,'destroyCustomer']);
/*
|--------------------------------------------------------------------------
*/
