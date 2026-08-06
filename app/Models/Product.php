<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'category_id',
        'name',
        'tamil_name',
        'english_alias',
        'slug',
        'short_desc',
        'description',
        'price_per_kg',
        'sale_price_per_kg',
        'image',
        'stock_quantity',
        'availability_status',
        'bone_type',
        'best_for',
        'nutrition_info',
        'rating',
        'reviews_count',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price_per_kg' => 'float',
        'sale_price_per_kg' => 'float',
        'stock_quantity' => 'float',
        'rating' => 'float',
        'nutrition_info' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getImageAttribute($value)
    {
        if ($value && str_starts_with($value, '/images/')) {
            return asset($value);
        }

        if ($value && filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('/images/fish_category.png');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cuttingStyles()
    {
        return $this->belongsToMany(CuttingStyle::class, 'product_cutting_style');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
