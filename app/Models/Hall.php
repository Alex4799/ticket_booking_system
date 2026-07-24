<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hall extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'cinema_id',
        'seat_column',
        'active',
        'du_id',
    ];
}
