<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\api\AuthController;


Route::post('customers', [CustomerController::class, 'signUpCustomer']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function (){
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('users/me', [UserController::class, 'showMyself'])->middleware('can:showMe,App\Models\User');

    // PRODUCTS
    Route::get('products', [ProductController::class, 'showAllProducts'])->middleware('can:viewAny,App\Models\Product');
    Route::get('products/hotdishes', [ProductController::class, 'showHotDishes'])->middleware('can:viewAny,App\Models\Product');
    Route::get('products/colddishes', [ProductController::class, 'showColdDishes'])->middleware('can:viewAny,App\Models\Product');
    Route::get('products/drinks', [ProductController::class, 'showDrinks'])->middleware('can:viewAny,App\Models\Product');
    Route::get('products/desserts', [ProductController::class, 'showDesserts'])->middleware('can:viewAny,App\Models\Product');
    Route::get('products/{id}', [ProductController::class, 'showProduct'])->where('id', '[0-9]+')->middleware('can:view,App\Models\Product');
    Route::post('products', [ProductController::class, 'createProduct'])->middleware('can:create,App\Models\Product');
    Route::put('products/{id}', [ProductController::class, 'editProduct'])->middleware('can:update,App\Models\Product');
    Route::delete('products/{id}', [ProductController::class,'deleteProduct'])->middleware('can:delete,App\Models\Product');
    Route::post('products/image', [ProductController::class, 'uploadProductImage'])->middleware('can:create,App\Models\Product');

    // USERS
    Route::get('users', [UserController::class, 'showAllUsers'])->middleware('can:viewAny,App\Models\User');
    Route::get('users/type/{type}', [UserController::class, 'showUsersByType'])->middleware('can:view,App\Models\User');
    Route::get('users/email/{email}', [UserController::class, 'showUsersByEmail'])->middleware('can:view,App\Models\User');
    Route::get('users/type/{type}/{email}', [UserController::class, 'showUsersByTypeAndEmail'])->middleware('can:view,App\Models\User');
    Route::get('users/{id}', [UserController::class, 'showUser'])->middleware('can:view,App\Models\User');
    Route::post('users', [UserController::class, 'signUpUser'])->middleware('can:create,App\Models\User');
    Route::put('users/block', [UserController::class, 'blockUser'])->middleware('can:blockOrUnblockUser,App\Models\User');
    Route::put('users/unblock', [UserController::class, 'unblockUser'])->middleware('can:blockOrUnblockUser,App\Models\User');
    Route::put('users/{id}', [UserController::class, 'editUserProfile'])->where('id', '[0-9]+')->middleware('can:update,App\Models\User');
    Route::put('users/{id}/password', [UserController::class, 'changePassword'])->middleware('can:update,App\Models\User');
    Route::delete('users/{id}', [UserController::class,'deleteUserAccount'])->middleware('can:delete,App\Models\User');
    Route::post('users/image', [UserController::class, 'uploadUserImage'])->middleware('can:update,App\Models\User');

    // CUSTOMERS
    Route::get('customers', [CustomerController::class, 'showAllCustomers'])->middleware('can:viewAny,App\Models\Customer');
    Route::get('customers/{id}', [CustomerController::class, 'showCustomer'])->middleware('can:view,App\Models\Customer');
    Route::get('customers/email/{email}', [CustomerController::class, 'showCustomersByEmail'])->middleware('can:view,App\Models\Customer');
    Route::put('customers/block', [CustomerController::class, 'blockCustomer'])->middleware('can:blockUnblockCustomer,App\Models\Customer');
    Route::put('customers/unblock', [CustomerController::class, 'unblockCustomer'])->middleware('can:blockUnblockCustomer,App\Models\Customer');
    Route::put('customers/{id}', [CustomerController::class, 'editCustomerProfile'])->middleware('can:update,App\Models\Customer');
    Route::delete('customers/{id}', [CustomerController::class,'deleteCustomerAccount'])->middleware('can:delete,App\Models\Customer');

    // ORDERS
    Route::get('orders', [OrderController::class, 'showAllOrders'])->middleware('can:viewAny,App\Models\Order');
    Route::get('orders/customer/{id}', [OrderController::class, 'showAllOrdersFromCustomer'])->middleware('can:view,App\Models\Order');
    Route::get('orders/status/{status}', [OrderController::class, 'showStatusOrders'])->whereIn('status', ['preparing', 'ready', 'delivered', 'cancelled'])->middleware('can:view,App\Models\Order');
    Route::get('orders/{id}', [OrderController::class, 'showOrder'])->middleware('can:view,App\Models\Order');
    Route::get('orders/customer/{id}/{status}', [OrderController::class, 'showStatusOrdersFromCustomer'])->middleware('can:view,App\Models\Order');
    Route::post('orders', [OrderController::class, 'createOrder'])->middleware('can:create,App\Models\Order');
    Route::put('orders/{id}/ready', [OrderController::class, 'setOrderToReady'])->middleware('can:update,App\Models\Order');
    Route::put('orders/{id}/deliver', [OrderController::class, 'deliverOrder'])->middleware('can:update,App\Models\Order');
    Route::put('orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->middleware('can:updateCancelOrder,App\Models\Order');

    // ORDER ITEMS
    Route::get('orderitems', [OrderItemController::class, 'showOrderItems'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/hotdishes', [OrderItemController::class, 'showHotDishes'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/hotdishes/{status}', [OrderItemController::class, 'showHotDishesByStatus'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/hotdishes/order/{order_id}', [OrderItemController::class, 'showHotDishesFromOrder'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/order/{order_id}', [OrderItemController::class, 'showOrderItemsFromOrder'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/order/{order_id}/{status}', [OrderItemController::class, 'showOrderItemsFromOrderByStatus'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/{id}', [OrderItemController::class, 'showOrderItem'])->middleware('can:viewAny,App\Models\OrderItem');
    Route::put('orderitems/{id}/{status}', [OrderItemController::class, 'changeStatusOrderItem'])->middleware('can:update,App\Models\OrderItem');

    // STATISTICS
    Route::get('statistics/{date}', [StatisticsController::class, 'countUser']);
    Route::get('statistics/type/{type}', [StatisticsController::class, 'countProductType'])->whereIn('type', ['hot dish', 'cold dish', 'drink', 'dessert']);
});