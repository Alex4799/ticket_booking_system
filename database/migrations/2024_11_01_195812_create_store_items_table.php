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
        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('product_name');
            $table->integer('qty')->default(1)->nullable();
            $table->integer('purchase_price');
            $table->integer('selling_price')->nullable();
            $table->integer('profit')->nullable();
            $table->integer('instock_level')->default(10)->nullable();
            $table->integer('store_id');
            $table->integer('type')->default(0)->nullable();//0 = default product, 1 = no need instock
            $table->integer('selling_type')->default(0)->nullable();//0 = default product, 1 = no selling
            $table->string('unit')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_items');
    }
};
