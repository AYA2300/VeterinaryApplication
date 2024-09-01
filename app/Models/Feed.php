<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'type',
        'image',
        'Detailes',
        'price',
        'Base_price',






    ];
}
