<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageProduct extends Model
{
    use HasFactory;

    protected $fillable=[
        'store_item_id',
        'product_name',
        'store_id',
        'qty',
        'total_price',
        'reduce_by',
    ];
}
