<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('delivery_type', ['delivery', 'pickup'])->default('delivery');
            $table->text('delivery_address')->nullable();
            $table->string('landmark')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('delivery_slot')->nullable();
            $table->string('payment_method')->default('COD'); // COD, UPI
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('status', [
                'awaiting_fulfilment',
                'final_bill_ready',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('awaiting_fulfilment');
            $table->decimal('estimated_subtotal', 10, 2)->default(0.00);
            $table->decimal('delivery_charge', 8, 2)->default(0.00);
            $table->decimal('discount_amount', 8, 2)->default(0.00);
            $table->decimal('estimated_total', 10, 2)->default(0.00);
            $table->decimal('final_subtotal', 10, 2)->nullable();
            $table->decimal('final_total', 10, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('cancellation_expires_at')->nullable();
            $table->timestamp('weight_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
