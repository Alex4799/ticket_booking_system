<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $fillable=[
        'payment_method',
        'total_price',
        'pay_amount',
        'change',
        'store_id',
        'order_id',
        'payment_slip',
        'product',
        'promotion_id',
        'promotion_price',
        'cinema_id',
        'status',
        'ticket_status',
        'order_from',
    ];

    protected $casts = [
        'product'=>'array',
    ];
}
