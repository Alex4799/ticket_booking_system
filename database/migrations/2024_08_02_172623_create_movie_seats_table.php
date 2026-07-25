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
        Schema::create('movie_seats', function (Blueprint $table) {
            $table->id();
            $table->integer('movie_plan_id');
            $table->integer('hall_id');
            $table->integer('seat_id');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_seats');
    }
};
