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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('main_genre');
            $table->json('genre')->nullable();
            $table->json('cast')->nullable();
            $table->string('director');
            $table->json('cover')->nullable();
            $table->string('poster')->nullable();
            $table->string('release_date');
            $table->string('run_time');
            $table->string('trailer');
            $table->string('rating');
            $table->string('subtitle');
            $table->string('language');
            $table->longText('summary');
            $table->string('start_date');
            $table->string('end_date');
            $table->integer('active')->default(0)->nullable();
            $table->integer('featured')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
