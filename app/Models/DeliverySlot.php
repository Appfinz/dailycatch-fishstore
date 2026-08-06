<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'time_range', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
