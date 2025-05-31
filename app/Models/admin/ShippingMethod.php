<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $table = 'shipping_methods';

    protected $fillable = [
        'name',
        'description',
        'cost',
        'estimated_days',
    ];
}
