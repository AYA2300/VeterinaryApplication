<?php

namespace App\Models;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pharmacy extends Model
{
    use HasFactory;
    protected $fillable=
    [
        'name','owner','open_time','close_time','address'

    ];

    protected $casts=
    [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
    ];

         public function add_medicine_pharmacy(Medicine $medicine){
            $this->medicines()->attach($medicine);
         }

     public function getPrice(){

        $med= $this->medicines;
        foreach($med as $name){
           return $name->pivot->price;
        }

        }

    //Relations
    /**many to many medicines */

    public function medicines(){
        return $this->beLongsToMany(Medicine::class,'pharmacy_medicines')->withPivot(['price']);
    }
    public function getOpeningHoursAttribute(){
        return $this->open_time->format('H:i A') .'- '. $this->close_time->format('H:i A');
    }


}
