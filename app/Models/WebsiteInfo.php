<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteInfo extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'contact',
        'logo',
        'cover_letter',
        'cover_image',
        'footer_letter',
        'about_image',
        'aboutus_letter',
    ];

    protected $casts = [
        'cover_image'=>'array',
        'about_image'=>'array',
    ];
}
