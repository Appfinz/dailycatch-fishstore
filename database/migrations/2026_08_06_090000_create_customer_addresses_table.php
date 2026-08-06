<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('label')->default('Home'); // Home, Work, Other
            $table->string('flat_no')->nullable();
            $table->string('street_address');
            $table->string('landmark')->nullable();
            $table->string('pincode')->default('600059');
            $table->decimal('latitude', 10, 7)->default(12.9249);
            $table->decimal('longitude', 10, 7)->default(80.1278);
            $table->decimal('distance_km', 5, 2)->default(0.5);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
