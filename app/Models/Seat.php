<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'hall_id',
        'seat_plan_id',
        'active',
        'seat_type',
    ];
}
