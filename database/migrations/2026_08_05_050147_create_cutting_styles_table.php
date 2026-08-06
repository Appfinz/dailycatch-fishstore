<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cutting_styles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Whole Fish, Curry Cut, Fry Cut, Boneless, Steak Cut
            $table->string('tamil_name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('additional_charge', 8, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cutting_styles');
    }
};
