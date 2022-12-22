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
    Route::get('users/me', [UserController::class, 'showMyself']); //->middleware('can:showMe,App\Models\User'); // works fine

    // PRODUCTS
    Route::get('products', [ProductController::class, 'showAllProducts']);//->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/hotdishes', [ProductController::class, 'showHotDishes']);//->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/colddishes', [ProductController::class, 'showColdDishes']);//->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/drinks', [ProductController::class, 'showDrinks']);//->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/desserts', [ProductController::class, 'showDesserts']);//->middleware('can:viewAny,App\Models\Product'); // works fine
    Route::get('products/{id}', [ProductController::class, 'showProduct']);//->where('id', '[0-9]+')->middleware('can:view,App\Models\Product'); // works fine
    Route::post('products', [ProductController::class, 'createProduct']);//->middleware('can:create,App\Models\Product'); // works fine
    Route::put('products/{id}', [ProductController::class, 'editProduct']);//->middleware('can:update,App\Models\Product'); // Works fine
    Route::delete('products/{id}', [ProductController::class,'deleteProduct']);//->middleware('can:delete,App\Models\Product'); // Works fine
    Route::post('products/image', [ProductController::class, 'uploadProductImage']);

    // USERS
    Route::get('users', [UserController::class, 'showAllUsers']);//->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/type/{type}', [UserController::class, 'showUsersByType']);//->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/email/{email}', [UserController::class, 'showUsersByEmail']);//->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/type/{type}/{email}', [UserController::class, 'showUsersByTypeAndEmail']);//->middleware('can:viewAny,App\Models\User'); // works fine
    Route::get('users/{id}', [UserController::class, 'showUser']);//->middleware('can:viewAny,App\Models\User'); // works fine
    Route::post('users', [UserController::class, 'signUpUser']);
    Route::put('users/block', [UserController::class, 'blockUser']);//->middleware('can:blockOrUnblockUser,App\Models\User'); // works fine
    Route::put('users/unblock', [UserController::class, 'unblockUser']);//->middleware('can:blockOrUnblockUser,App\Models\User'); // works fine
    Route::put('users/{id}', [UserController::class, 'editUserProfile']);//->where('id', '[0-9]+')->middleware('can:update,App\Models\User'); // não tenho
    Route::put('users/{id}/password', [UserController::class, 'changePassword']);
    Route::delete('users/{id}', [UserController::class,'deleteUserAccount']);//->middleware('can:delete,App\Models\User'); // works fine
    Route::post('users/image', [UserController::class, 'uploadUserImage']);

    // CUSTOMERS
    Route::get('customers', [CustomerController::class, 'showAllCustomers']);//->middleware('can:viewAny,App\Models\Customer'); // works fine
    Route::get('customers/{id}', [CustomerController::class, 'showCustomer']);//->middleware('can:view,App\Models\Customer'); // works fine
    Route::get('customers/email/{email}', [CustomerController::class, 'showCustomersByEmail']); // não tenho
    Route::put('customers/block', [CustomerController::class, 'blockCustomer']);//->middleware('can:update,App\Models\Customer'); // não tenho
    Route::put('customers/unblock', [CustomerController::class, 'unblockCustomer']); // não tenho 
    Route::put('customers/{id}', [CustomerController::class, 'editCustomerProfile']);//->middleware('can:update,App\Models\Customer');
    Route::put('customers/points/add/{user_id}', [CustomerController::class, 'addPointsCustomer']);
    Route::put('customers/points/remove/{user_id}', [CustomerController::class, 'removePointsCustomer']);
    Route::delete('customers/{id}', [CustomerController::class,'deleteCustomerAccount']);//->middleware('can:delete,App\Models\Customer'); // works fine

    // ORDERS
    Route::get('orders', [OrderController::class, 'showAllOrders']);//->middleware('can:viewAny,App\Models\Order'); // works fine
    Route::get('orders/customer/{id}', [OrderController::class, 'showAllOrdersFromCustomer']);//->middleware('can:view,App\Models\Order');
    Route::get('orders/status/{status}', [OrderController::class, 'showStatusOrders']);//->whereIn('status', ['C', 'D', 'R', 'P'])->middleware('can:viewAny,App\Models\Order'); // works fine
    Route::get('orders/{id}', [OrderController::class, 'showOrder']);//->middleware('can:view,App\Models\Order'); // testar ja alterei a rota acima da status q dava problema
    Route::get('orders/customer/{id}/{status}', [OrderController::class, 'showStatusOrdersFromCustomer']);
    Route::post('orders', [OrderController::class, 'createOrder']);//->middleware('can:create,App\Models\Order'); // works fine
    Route::put('orders/{id}/ready', [OrderController::class, 'setOrderToReady']);//>middleware('can:update,App\Models\Order'); // works fine
    Route::put('orders/{id}/deliver', [OrderController::class, 'deliverOrder']);//->middleware('can:update,App\Models\Order'); // works fine
    Route::put('orders/{id}/cancel', [OrderController::class, 'cancelOrder']);//->middleware('can:updateCancelOrder,App\Models\Order'); // works fine

    // ORDER ITEMS
    Route::get('orderitems', [OrderItemController::class, 'showOrderItems']);//->middleware('can:viewAny,App\Models\OrderItem');
    Route::get('orderitems/hotdishes', [OrderItemController::class, 'showHotDishes']); // não tenho
    Route::get('orderitems/hotdishes/{status}', [OrderItemController::class, 'showHotDishesByStatus']);
    Route::get('orderitems/hotdishes/order/{order_id}', [OrderItemController::class, 'showHotDishesFromOrder']);
    Route::get('orderitems/order/{order_id}', [OrderItemController::class, 'showOrderItemsFromOrder']);
    Route::get('orderitems/order/{order_id}/{status}', [OrderItemController::class, 'showOrderItemsFromOrderByStatus']);
    Route::get('orderitems/{id}', [OrderItemController::class, 'showOrderItem']);
    Route::put('orderitems/{id}/{status}', [OrderItemController::class, 'changeStatusOrderItem']);

    // STATISTICS
    Route::get('statistics/{date}', [StatisticsController::class, 'countUser']);
});