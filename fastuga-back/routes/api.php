<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\api\AuthController;

Route::post('login', [AuthController::class, 'login']);
// temporario enquanto nao tenho o middleware feito
Route::post('customers', [CustomerController::class, 'signUpCustomer']);
Route::post('users', [UserController::class, 'signUpUser']);
Route::get('products', [ProductController::class, 'showAllProducts']);
Route::get('users', [UserController::class, 'showAllUsers']);

Route::middleware('auth:api')->group(function (){
    
    Route::post('logout', [AuthController::class, 'logout']);

    // adicionar o middleware especifico para cada endpoint com as policies

    // PRODUCTS
    //Route::get('products', [ProductController::class, 'showAllProducts']);
    Route::get('products/{id}', [ProductController::class, 'showProduct']);
    Route::get('products/hotdishes', [ProductController::class, 'showHotDishes']);
    Route::get('products/colddishes', [ProductController::class, 'showColdDishes']);
    Route::get('products/drinks', [ProductController::class, 'showDrinks']);
    Route::get('products/desserts', [ProductController::class, 'showDesserts']);
    Route::post('products', [ProductController::class, 'createProduct']);
    Route::put('products/{id}', [ProductController::class, 'editProduct']);
    Route::delete('products/{id}', [ProductController::class,'deleteProduct']);

    // USERS
    Route::get('users/me', [UserController::class, 'showMe']);
    //Route::get('users', [UserController::class, 'showAllUsers']);
    Route::get('users/{id}', [UserController::class, 'showUser']);
    //Route::post('users', [UserController::class, 'signUpUser']);
    Route::put('users/{id}', [UserController::class, 'editUserProfile']);
    Route::delete('users/{id}', [UserController::class,'deleteUserAccount']);

    // CUSTOMERS
    Route::get('customers', [CustomerController::class, 'showAllCustomers']);
    Route::get('customers/{id}', [CustomerController::class, 'showCustomer']);
    //Route::post('customers', [CustomerController::class, 'signUpCustomer']);
    Route::put('customers/{id}', [CustomerController::class, 'editCustomerProfile']);
    Route::delete('customers/{id}', [CustomerController::class,'deleteCustomerAccount']);

    // ORDERS
    Route::get('orders', [OrderController::class, 'showAllOrders']);
    Route::get('orders/customer/{id}', [OrderController::class, 'showAllOrdersFromCustomer']);
    Route::get('orders/{id}', [OrderController::class, 'showOrder']);
    Route::post('orders', [OrderController::class, 'createOrder']);
    Route::put('orders/{id}/ready', [OrderController::class, 'setOrderToReady']);
    Route::put('orders/{id}/deliver', [OrderController::class, 'deliverOrder']);
    Route::put('orders/{id}/cancel', [OrderController::class, 'cancelOrder']);
});