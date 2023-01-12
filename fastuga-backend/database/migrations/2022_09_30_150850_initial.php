<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['C', 'EC', 'ED', 'EM']);
            // C "Customer"; EC "Employee - Chef"; ED "Employee - Delivery"; EM "Employee - Manager";
            $table->boolean('blocked')->default(false);
            $table->string('photo_url')->nullable();
            $table->json('custom')->nullable();
            $table->softDeletes();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('phone', 20);
            $table->integer('points');
            $table->string('nif', 9)->nullable();
            $table->enum('default_payment_type', ['VISA', 'PAYPAL', 'MBWAY'])->nullable();
            $table->string('default_payment_reference')->nullable();

            $table->json('custom')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['hot dish', 'cold dish', 'drink', 'dessert']);
            $table->string('description');
            $table->string('photo_url');
            $table->decimal('price', 8, 2);
            $table->json('custom')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_number');
            $table->enum('status', ['P', 'R', 'D', 'C']);
            // P "Preparing", R "Ready", D "Delivered", C "Cancelled"
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->decimal('total_price', 8, 2);
            $table->decimal('total_paid', 8, 2);
            $table->decimal('total_paid_with_points', 8, 2);
            $table->integer('points_gained');
            $table->integer('points_used_to_pay');
            $table->enum('payment_type', ['VISA', 'PAYPAL', 'MBWAY'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->date('date');                          // Order date (only the day)
            // The Employee that delivered the order
            // null if order was not delivered (status != "D")
            $table->bigInteger('delivered_by')->unsigned()->nullable();
            $table->foreign('delivered_by')->references('id')->on('users');

            $table->json('custom')->nullable();

            // Time related information about the order
            $table->timestamps();

            // Index by date & by status for faster queries
            $table->index('date');
            $table->index('status');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('order_local_number');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->enum('status', ['W', 'P', 'R']);
            // W "Waiting", P "Preparing", R "Ready"
            $table->decimal('price', 8, 2);
            // The chef that is preparing or has prepared this specific order_item
            // null if item is waiting for being prepared
            $table->bigInteger('preparation_by')->unsigned()->nullable();
            $table->foreign('preparation_by')->references('id')->on('users');
            $table->text('notes')->nullable();
            $table->json('custom')->nullable();
            $table->unique(['order_id', 'order_local_number']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
        Schema::table('users', function ($table) {
            $table->dropColumn(['type', 'blocked', 'photo_url', 'custom']);
        });
    }
};
