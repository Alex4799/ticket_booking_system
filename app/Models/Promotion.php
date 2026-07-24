<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'image',
        'cinema_id',
        'percentage',
        'amount',
        'promo_code',
        'start_date',
        'end_date',
        'active',
        'feature',
    ];
}
