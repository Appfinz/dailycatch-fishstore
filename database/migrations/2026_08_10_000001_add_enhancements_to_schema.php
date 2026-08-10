<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add has_weight_variation to products table
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'has_weight_variation')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_weight_variation')->default(true)->after('availability_status');
            });
        }

        // 2. Add additional_charge to product_cutting_style pivot table
        if (Schema::hasTable('product_cutting_style') && !Schema::hasColumn('product_cutting_style', 'additional_charge')) {
            Schema::table('product_cutting_style', function (Blueprint $table) {
                $table->decimal('additional_charge', 8, 2)->nullable()->after('cutting_style_id');
            });
        }

        // 3. Add preorder fields to orders table
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'is_preorder')) {
                    $table->boolean('is_preorder')->default(false)->after('delivery_slot');
                }
                if (!Schema::hasColumn('orders', 'delivery_date')) {
                    $table->date('delivery_date')->nullable()->after('is_preorder');
                }
                if (!Schema::hasColumn('orders', 'preorder_discount')) {
                    $table->decimal('preorder_discount', 8, 2)->default(0.00)->after('discount_amount');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'has_weight_variation')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_weight_variation');
            });
        }

        if (Schema::hasTable('product_cutting_style') && Schema::hasColumn('product_cutting_style', 'additional_charge')) {
            Schema::table('product_cutting_style', function (Blueprint $table) {
                $table->dropColumn('additional_charge');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['is_preorder', 'delivery_date', 'preorder_discount']);
            });
        }
    }
};
