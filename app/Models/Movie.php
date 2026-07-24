<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'name',
        'main_genre',
        'genre',
        'cast',
        'director',
        'poster',
        'cover',
        'release_date',
        'run_time',
        'trailer',
        'rating',
        'summary',
        'subtitle',
        'language',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'genre'=>'array',
        'cast'=>'array',
        'cover'=>'array',
    ];

}
