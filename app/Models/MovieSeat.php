<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSeat extends Model
{
    use HasFactory;
    // protected $connection = 'mysql_plan_seat';
    protected $fillable=[
        'movie_plan_id',
        'hall_id',
        'seat_id',
        'status',
    ];
}
