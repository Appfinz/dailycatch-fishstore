<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'branch_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'delivery_type',
        'delivery_address',
        'landmark',
        'latitude',
        'longitude',
        'delivery_slot',
        'is_preorder',
        'delivery_date',
        'payment_method',
        'payment_status',
        'status',
        'estimated_subtotal',
        'delivery_charge',
        'discount_amount',
        'preorder_discount',
        'estimated_total',
        'final_subtotal',
        'final_total',
        'admin_notes',
        'cancellation_expires_at',
        'weight_updated_at',
    ];

    protected $casts = [
        'is_preorder' => 'boolean',
        'delivery_date' => 'date',
        'estimated_subtotal' => 'float',
        'delivery_charge' => 'float',
        'discount_amount' => 'float',
        'preorder_discount' => 'float',
        'estimated_total' => 'float',
        'final_subtotal' => 'float',
        'final_total' => 'float',
        'cancellation_expires_at' => 'datetime',
        'weight_updated_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
