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
        'image',
        'prevention_methods'



    ];

    public function medicines(){
        return $this->belongsToMany(Medicine::class,'disease_medicine');
    }
}
