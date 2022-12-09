<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\api\AuthController;

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
Route::get('users', [UserController::class, 'showAllUsers']);
Route::get('user/{id}', [UserController::class, 'showUser']);
Route::post('user', [UserController::class, 'signUpUser']);
Route::put('user/{id}', [UserController::class, 'editUserProfile']);
Route::delete('user/{id}', [UserController::class,'deleteUserAccount']);

Route::get('customers', [CustomerController::class, 'showAllCustomers']);
Route::get('customer/{id}', [CustomerController::class, 'showCustomer']);
Route::post('customer', [CustomerController::class, 'signUpCustomer']);
Route::put('customer/{id}', [CustomerController::class, 'editCustomerProfile']);
Route::delete('customer/{id}', [CustomerController::class,'deleteCustomerAccount']);

/* AUTH
|--------------------------------------------------------------------------
*/
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

/* ORDERS - rotas CRUD dos users
|--------------------------------------------------------------------------
*/
Route::get('orders', [OrderController::class, 'showAllOrders']);
Route::get('orders/customer/{id}', [OrderController::class, 'showAllOrdersFromCustomer']);
Route::get('order/{id}', [OrderController::class, 'showOrder']);
Route::post('order', [OrderController::class, 'createOrder']);
Route::put('order/{id}/ready', [OrderController::class, 'setOrderToReady']);
Route::put('order/{id}/deliver', [OrderController::class, 'deliverOrder']);
Route::put('order/{id}/cancel', [OrderController::class, 'cancelOrder']);