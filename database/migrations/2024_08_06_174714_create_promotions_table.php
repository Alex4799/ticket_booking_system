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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('image');
            $table->string('promo_code');
            $table->integer('cinema_id')->default(0);
            $table->integer('percentage')->nullable();
            $table->integer('amount')->nullable();
            $table->string('start_date');
            $table->string('end_date');
            $table->integer('used_count');
            $table->integer('max_count');
            $table->integer('active')->default(0);
            $table->integer('feature')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
