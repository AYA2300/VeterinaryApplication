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
        'expiration_date',
        'status'
    ];

    //Relation many to mant with pharmacy
    public function pharmacies(){
        return$this->beLongsToMany(Pharmacy::class,'pharmacy_medicines')->withPivot(['price']);
    }

}
