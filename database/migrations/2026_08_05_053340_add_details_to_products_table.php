<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('bone_type')->default('single_bone')->after('availability_status'); // single_bone, low_bone, boneless, multi_bone
            $table->string('best_for')->default('curry_fry')->after('bone_type'); // curry, fry, grill, soup, biryani
            $table->json('nutrition_info')->nullable()->after('best_for');
            $table->decimal('rating', 3, 2)->default(4.80)->after('nutrition_info');
            $table->integer('reviews_count')->default(12)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['bone_type', 'best_for', 'nutrition_info', 'rating', 'reviews_count']);
        });
    }
};
