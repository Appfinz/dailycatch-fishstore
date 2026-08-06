<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('cutting_style_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name');
            $table->string('cutting_style_name')->nullable();
            $table->decimal('unit_price_per_kg', 10, 2);
            $table->decimal('cutting_charge', 8, 2)->default(0.00);
            $table->decimal('requested_qty_kg', 8, 2);
            $table->decimal('estimated_item_total', 10, 2);
            $table->decimal('actual_qty_kg', 8, 2)->nullable();
            $table->decimal('final_item_total', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
