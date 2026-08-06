<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingStyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tamil_name',
        'description',
        'image',
        'additional_charge',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'additional_charge' => 'float',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_cutting_style');
    }
}
