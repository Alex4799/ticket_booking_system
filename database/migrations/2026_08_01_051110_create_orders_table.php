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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_phone')->nullable();
            $table->integer('payment_method');
            $table->string('promotion_id')->nullable();
            $table->integer('promotion_price')->nullable();
            $table->integer('total_price');
            $table->integer('pay_amount')->nullable();
            $table->integer('change')->default(0)->nullable();
            $table->string('movie_name');
            $table->string('show_time');
            $table->date('date');
            $table->integer('hall_id');
            $table->integer('cinema_id');
            $table->json('genre')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_reference_number')->nullable();
            $table->string('signature')->nullable();
            $table->string('payment_slip')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_fee')->nullable();
            $table->integer('movie_plan_id');
            $table->json('seats');
            $table->integer('seat_count');
            $table->integer('status')->default(0)->nullable();//0=pending,1=success,2=reject,3=cancel,4=booking
            $table->integer('ticket_status')->default(0)->nullable();//0=no,1=yes,
            $table->integer('order_from')->default(0)->nullable();//0=seller,1=online,
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
