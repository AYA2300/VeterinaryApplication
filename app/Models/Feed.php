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
        'Description',
        'Composition',

        'price',
        'Base_price',
    ];

    //Relation
    public function carts(){
        return $this->morphToMany(Cart::class,'itemable');
    }

    public function orders(){
        return $this->morphMany(OrderItem::class,'itemable');
    }
}
