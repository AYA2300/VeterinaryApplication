<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $fillable=['name','image','animal_categorie_id'];


    public function breeders()
    {
        return $this->belongsToMany(Breeder::class, 'breeder_community');
    }




    // علاقة One-to-Many بين المجتمع والرسائل
    public function group_messages()
    {
        return $this->hasMany(Group_Message::class);
    }


    public function admins()
{
    return $this->belongsToMany(Admin::class, 'admin_community');
}

public function animalType()
    {
        return $this->belongsTo(AnimalCategorie::class);
    }



}
