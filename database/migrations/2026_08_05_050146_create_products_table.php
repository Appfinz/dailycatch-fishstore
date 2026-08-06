<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('tamil_name')->nullable();
            $table->string('english_alias')->nullable();
            $table->string('slug')->unique();
            $table->text('short_desc')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('sale_price_per_kg', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->decimal('stock_quantity', 8, 2)->default(50.00);
            $table->enum('availability_status', ['in_stock', 'out_of_stock', 'limited'])->default('in_stock');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
