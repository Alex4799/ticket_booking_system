<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable=[
        'supplier_id',
        'product_id',
        'product_name',
        'store_id',
        'qty',
        'price',
        'total_price',
        'status',
    ];
}
