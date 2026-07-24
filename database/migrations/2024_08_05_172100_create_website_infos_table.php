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
        Schema::create('website_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('contact');
            $table->string('logo')->nullable();
            $table->longText('cover_letter');
            $table->json('cover_image');
            $table->longText('footer_letter');
            $table->json('about_image');
            $table->longText('aboutus_letter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_infos');
    }
};
