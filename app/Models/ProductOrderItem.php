<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'profit',
        'store_id',
        'instock',
        'qty',
        'created_at'
    ];
}
