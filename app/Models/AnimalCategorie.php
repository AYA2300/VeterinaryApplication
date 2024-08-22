<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalCategorie extends Model
{
    use HasFactory;
    protected $fillable=[
        'name'
    ];

    public function breeder(){
        return $this->belongsTo(Breeder::class,'category_id','id');

    }
}
