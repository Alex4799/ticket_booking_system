<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable=[
        'send_store_id',
        'store_item_id',
        'product_name',
        'receive_store_id',
        'qty',
        'price',
        'total_price',
        'additional_fees',
        'status',
    ];
}
