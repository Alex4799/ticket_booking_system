<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_method')->nullable();
            $table->integer('total_price');
            $table->integer('pay_amount')->nullable();
            $table->integer('change')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('payment_slip')->nullable();
            $table->json('product');
            $table->string('promotion_id')->nullable();
            $table->integer('promotion_price')->nullable();
            $table->integer('cinema_id');
            $table->integer('status')->default(0);//0=pending,1=success,2=reject,3=cancel,4=booking
            $table->integer('ticket_status')->default(0);//0=no,1=yes,
            $table->integer('order_from')->default(0);//0=seller,1=online,
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
