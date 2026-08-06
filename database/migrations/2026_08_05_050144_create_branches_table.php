<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address');
            $table->string('city')->default('Chennai');
            $table->string('pincode')->default('600059');
            $table->string('phone')->default('918778199218');
            $table->string('email')->default('support@dailycatchfishshop.com');
            $table->decimal('latitude', 10, 7)->default(12.9249);
            $table->decimal('longitude', 10, 7)->default(80.1278);
            $table->decimal('delivery_radius_km', 5, 2)->default(3.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
