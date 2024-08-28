<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
          protected $fillable=
          [
            'veterinary_id','breeder_id'
          ];

    public function messages()
    {
        return $this->hasMany(Message::class);

    }

    public function breeder(){
        return $this->beLongsTo(Breeder::class,'breeder_id');
    }


    public function Veterinarian(){
        return $this->beLongsTo(Veterinarian::class,'veterinary_id');
    }



}
