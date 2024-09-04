<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itemable extends Model
{
    use HasFactory;
    protected $fillable=
    [
        'cart_id','quantity'
    ];

    public function itemable(){
        return $this->morphTo();
    }


}
