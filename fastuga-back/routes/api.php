<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\api\AuthController;

//Route::post('login', [AuthController::class, 'login']);

// temporario enquanto nao tenho o middleware feito
/* Route::post('customers', [CustomerController::class, 'signUpCustomer']);
Route::post('users', [UserController::class, 'signUpUser']);
Route::get('products', [ProductController::class, 'showAllProducts']);
Route::get('users', [UserController::class, 'showAllUsers']);
Route::get('products/colddishes', [ProductController::class, 'showColdDishes']);
Route::get('products/hotdishes', [ProductController::class, 'showHotDishes']);
Route::get('products/drinks', [ProductController::class, 'showDrinks']);
Route::get('products/desserts', [ProductController::class, 'showDesserts']);
Route::get('customers', [CustomerController::class, 'showAllCustomers']);
Route::get('users/chef', [UserController::class, 'showAllChefEmployees']);
Route::get('users/delivery', [UserController::class, 'showAllDeliveryEmployees']);
Route::get('users/manager', [UserController::class, 'showAllManagerEmployees']);
Route::get('orders', [OrderController::class, 'showAllOrders']);
Route::get('orders/{status}', [OrderController::class, 'showStatusOrders']);
Route::get('orders/customer/{id}', [OrderController::class, 'showOrdersFromCustomer']);
Route::get('orders/customer/{id}/{status}', [OrderController::class, 'showStatusOrdersFromCustomer']);
Route::get('customers/{id}', [CustomerController::class, 'showCustomer']); */


Route::post('customers', [CustomerController::class, 'signUpCustomer']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function (){
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('users/me', [UserController::class, 'showMyself']);



    // adicionar o middleware especifico para cada endpoint com as policies

    // PRODUCTS
    Route::get('products', [ProductController::class, 'showAllProducts']);
    Route::get('products/hotdishes', [ProductController::class, 'showHotDishes']);
    Route::get('products/colddishes', [ProductController::class, 'showColdDishes']);
    Route::get('products/drinks', [ProductController::class, 'showDrinks']);
    Route::get('products/desserts', [ProductController::class, 'showDesserts']);
    Route::get('products/{id}', [ProductController::class, 'showProduct']);
    Route::post('products', [ProductController::class, 'createProduct']);
    Route::put('products/{id}', [ProductController::class, 'editProduct']);
    Route::delete('products/{id}', [ProductController::class,'deleteProduct']);
    Route::post('products/{id}/image', [ProductController::class, 'uploadProductImage']);

    // USERS
    Route::get('users', [UserController::class, 'showAllUsers']);
    Route::get('users/type/{type}', [UserController::class, 'showUsersByType']);
    Route::get('users/email/{email}', [UserController::class, 'showUsersByEmail']);
    Route::get('users/type/{type}/{email}', [UserController::class, 'showUsersByTypeAndEmail']);
    Route::get('users/{id}', [UserController::class, 'showUser']);
    Route::post('users', [UserController::class, 'signUpUser']);
    Route::put('users/{id}', [UserController::class, 'editUserProfile']);
    Route::put('users/{id}/block', [UserController::class, 'blockUserAccount']); // isto podia ser query parameter
    Route::delete('users/{id}', [UserController::class,'deleteUserAccount']);

    // CUSTOMERS
    Route::get('customers', [CustomerController::class, 'showAllCustomers']);
    Route::get('customers/{id}', [CustomerController::class, 'showCustomer']);
    Route::get('customers/email/{email}', [CustomerController::class, 'showCustomersByEmail']);
    Route::put('customers/{id}', [CustomerController::class, 'editCustomerProfile']);
    Route::delete('customers/{id}', [CustomerController::class,'deleteCustomerAccount']);
    

    // ORDERS
    Route::get('orders', [OrderController::class, 'showAllOrders']);
    Route::get('orders/customer/{id}', [OrderController::class, 'showAllOrdersFromCustomer']);
    Route::get('orders/{status}', [OrderController::class, 'showStatusOrders']);
    Route::get('orders/{id}', [OrderController::class, 'showOrder']);
    Route::get('orders/customer/{id}/{status}', [OrderController::class, 'showStatusOrdersFromCustomer']);
    Route::post('orders', [OrderController::class, 'createOrder']);
    Route::put('orders/{id}/ready', [OrderController::class, 'setOrderToReady']); // isto podia ser query parameter
    Route::put('orders/{id}/deliver', [OrderController::class, 'deliverOrder']);
    Route::put('orders/{id}/cancel', [OrderController::class, 'cancelOrder']);

    
    
    
});