<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable=
    [
        'name',
        'image',
        'usage',
         'category',
         'type_of_medicine',
         'price',
        'status'
    ];


    public function diseases(){
        return $this->belongsToMany(Diseases::class,'disease_medicine');
    }

    //Relation many to mant with pharmacy
    public function pharmacies(){
        return$this->beLongsToMany(Pharmacy::class,'pharmacy_medicines')->withPivot(['price']);
    }


}
