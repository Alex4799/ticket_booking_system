<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_id',
        'product_name',
        'qty',
        'purchase_price',
        'selling_price',
        'profit',
        'instock_level',
        'store_id',
        'type',
        'selling_type',
        'unit',
        'barcode',
        'active',
    ];
}
