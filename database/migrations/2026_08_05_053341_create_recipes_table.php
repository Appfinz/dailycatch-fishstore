<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->string('tamil_title')->nullable();
            $table->string('slug')->unique();
            $table->text('short_desc')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('instructions')->nullable();
            $table->string('prep_time')->default('15 Mins');
            $table->string('cook_time')->default('25 Mins');
            $table->string('servings')->default('4 Persons');
            $table->string('difficulty')->default('Medium');
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
