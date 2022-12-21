<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\api\AuthController;


Route::post('customers', [CustomerController::class, 'signUpCustomer']);
Route::post('login', [AuthController::class, 'login']);
Route::post('users', [UserController::class, 'signUpUser']);


Route::middleware('auth:api')->group(function (){
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api'); // works fine
    Route::get('users/me', [UserController::class, 'showMyself'])->middleware('can:showMe,App\Models\User'); // works fine

    // PRODUCTS
    Route::get('products', [ProductController::class, 'index'])->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/{id}', [ProductController::class, 'show'])->where('id', '[0-9]+')->middleware('can:view,App\Models\Product'); // works fine
    Route::get('products/hotdishes', [ProductController::class, 'showHotDishes'])->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/colddishes', [ProductController::class, 'showColdDishes'])->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/drinks', [ProductController::class, 'showDrinks'])->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/desserts', [ProductController::class, 'showDesserts'])->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::post('products', [ProductController::class, 'store'])->middleware('can:create,App\Models\Product'); // works fine
    Route::post('products/image', [ProductController::class, 'uploadProductImage'])->middleware('can:create,App\Models\Product'); // works fine
    Route::put('products/{id}', [ProductController::class, 'update'])->middleware('can:update,App\Models\Product'); // Works fine
    Route::delete('products/{id}', [ProductController::class,'destroy'])->middleware('can:delete,App\Models\Product'); // Works fine
    
    // USERS
    Route::get('users', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/manager', [UserController::class, 'showAllManagerEmployees'])->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/chef', [UserController::class, 'showAllChefEmployees'])->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/delivery', [UserController::class, 'showAllDeliveryEmployees'])->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/{id}', [UserController::class, 'show'])->middleware('can:view,App\Models\User'); // works fine
    Route::post('users/image', [UserController::class, 'uploadUserImage'])->middleware('can:create,App\Models\User'); // works fine
    Route::put('users/{id}', [UserController::class, 'update'])->where('id', '[0-9]+')->middleware('can:update,App\Models\User'); 
    Route::put('users/block', [UserController::class, 'blockUser'])->middleware('can:blockOrUnblockUser,App\Models\User');
    Route::put('users/unblock', [UserController::class, 'unblockUser'])->middleware('can:blockOrUnblockUser,App\Models\User');
    Route::delete('users/{id}', [UserController::class,'destroy'])->middleware('can:delete,App\Models\User'); // works fine

    // CUSTOMERS
    Route::get('customers', [CustomerController::class, 'index'])->middleware('can:viewAny,App\Models\Customer'); // works fine
    Route::get('customers/{id}', [CustomerController::class, 'show'])->middleware('can:view,App\Models\Customer'); // works fine
    Route::put('customers/block', [CustomerController::class, 'blockCustomer'])->middleware('can:block_Customer,App\Models\Customer');
    Route::put('customers/unblock', [CustomerController::class, 'unblockCustomer'])->middleware('can:unblock_Customer,App\Models\Customer'); 
    Route::put('customers/{id}', [CustomerController::class, 'update'])->middleware('can:update,App\Models\Customer'); 
    Route::delete('customers/{id}', [CustomerController::class,'destroy'])->middleware('can:delete,App\Models\Customer'); // works fine

    // ORDERS
    Route::get('orders', [OrderController::class, 'index'])->middleware('can:viewAny,App\Models\Order'); // works fine
    Route::get('orders/customer/{id}', [OrderController::class, 'showAllOrdersFromCustomer'])->middleware('can:view,App\Models\Order'); 
    Route::get('orders/{status}', [OrderController::class, 'showStatusOrders'])->middleware('can:viewAny,App\Models\Order'); // works fine
    Route::get('orders/{id}', [OrderController::class, 'showOrder']);
    Route::get('orders/customer/{id}/{status}', [OrderController::class, 'showStatusOrdersFromCustomer']);
    Route::post('orders', [OrderController::class, 'createOrder'])->middleware('can:create,App\Models\Order'); // works fine
    Route::put('orders/{id}/ready', [OrderController::class, 'setOrderToReady']); // isto podia ser query parameter
    Route::put('orders/{id}/deliver', [OrderController::class, 'deliverOrder']);
    Route::put('orders/{id}/cancel', [OrderController::class, 'cancelOrder']);

    // ORDER ITEMS
    Route::get('orderitems', [OrderItemController::class, 'showOrderItems'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/hotdishes', [OrderItemController::class, 'showOrderItems']);
    Route::get('orderitems/{status}', [OrderItemController::class, 'showHotDishesByStatus']);
});