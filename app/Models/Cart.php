<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable=[
     'breeder_id'
    ];

    public function breeder(){
        return $this->beLongsTo(Breeder::class);
    }
    //morphs

      public function medicines()
      {
        return $this->morphedByMany(Medicine::class,'itemable')->withPivot('quantity');;
      }

      public function feeds()
      {
        return $this->morphedByMany(Feed::class,'itemable')->withPivot('quantity');;
      }
            ///-------------------------------
            public function orders()
            {
                return $this->hasMany(Order::class);
            }

}
