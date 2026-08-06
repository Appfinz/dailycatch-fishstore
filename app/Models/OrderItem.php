<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'cutting_style_id',
        'product_name',
        'cutting_style_name',
        'unit_price_per_kg',
        'cutting_charge',
        'requested_qty_kg',
        'estimated_item_total',
        'actual_qty_kg',
        'final_item_total',
    ];

    protected $casts = [
        'unit_price_per_kg' => 'float',
        'cutting_charge' => 'float',
        'requested_qty_kg' => 'float',
        'estimated_item_total' => 'float',
        'actual_qty_kg' => 'float',
        'final_item_total' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cuttingStyle()
    {
        return $this->belongsTo(CuttingStyle::class);
    }
}
