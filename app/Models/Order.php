<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    // protected $connection = 'mysql_order';
    protected $fillable=[
        'invoice',
        'user_name',
        'user_email',
        'user_phone',
        'payment_method',
        'promotion_id',
        'promotion_price',
        'total_price',
        'pay_amount',
        'change',
        'movie_name',
        'show_time',
        'date',
        'genre',
        'hall_id',
        'transaction_id',
        'payment_type',
        'movie_plan_id',
        'seats',
        'seat_count',
        'status',
        'ticket_status',
        'order_from'
    ];

    protected $casts = [
        'seats'=>'array',
    ];
}
