<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diseases extends Model
{
    use HasFactory;
    protected $fillable=[

        'name',
        'treatment',
        'causes',
        'symptoms',
        'image'



    ];

    public function medicines(){
        return $this->belongsToMany(Medicine::class,'disease_medicine');
    }
}
