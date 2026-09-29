<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSeat extends Model
{
    use HasFactory;
    protected $fillable=[
        'movie_plan_id',
        'hall_id',
        'seat_id',
        'price',
        'status',
    ];
}
