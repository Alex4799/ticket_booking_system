<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable=[
        'se_email',
        're_email',
        'title',
        'message',
        'reply_id',
        'status',
    ];
}
