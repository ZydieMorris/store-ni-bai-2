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
         Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // Snapshot of product data
            $table->string('product_name');          // Product name at time of order
            $table->integer('quantity');             // Number of units purchased
            $table->decimal('unit_price', 10, 2);    // Price per unit at purchase
            $table->decimal('total_price', 10, 2);   // quantity * unit_price

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
