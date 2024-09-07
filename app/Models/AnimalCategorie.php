<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnimalCategorie extends Model
{
    use HasFactory;
    protected $fillable=[
        'name'
    ];
    protected $hidden=[
        'pivot'
    ];

    public function breeders()
    {
        return $this->belongsToMany(Breeder::class, 'animal_categorie_breeder');
    }

    public function community():HasOne
    {
        return $this->hasOne(Community::class);
    }






}
