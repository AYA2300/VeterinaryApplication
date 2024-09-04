<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'delivery_time','name','delivery_price'

    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }


}
