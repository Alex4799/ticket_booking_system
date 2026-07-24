<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoviePlan extends Model
{
    use HasFactory;
    protected $connection = 'mysql_plan_seat';
    protected $fillable=[
        'movie_id',
        'movie_name',
        'hall_id',
        'date',
        'start_time',
        'end_time',
        'clean_time',
        'online_book',
        'online_buy',
        'summary',
        'note',
        'active'
    ];

    protected $casts=[
        'summary'=>'array'
    ];
}
