<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    // protected $connection = 'mysql_plan_seat';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movie_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('movie_id');
            $table->string('movie_name')->nullable();
            $table->integer('hall_id');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('clean_time');
            $table->string('date');
            $table->integer('online_book')->default(1);
            $table->integer('online_buy')->default(1);
            $table->json('summary')->nullable();
            $table->longText('note')->nullable();
            $table->integer('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_plans');
    }
};
