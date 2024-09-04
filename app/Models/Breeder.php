<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Breeder  extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,HasRoles,HasApiTokens;



    protected $fillable=[
        'name',
        'phone_number',
        'confirm_password',
        'password',
        'role',
        'region'


    ];

      /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function animalCategories()
    {
        return $this->belongsToMany(AnimalCategorie::class, 'animal_categorie_breeder');
    }

      //Relations
      public function conversations()
      {
         return $this->hasMany(Conversation::class,'breeder_id');

      }
      //morph type send if vet

        public function messages()
        {
         return $this->morphMany(Message::class,'messageable_sender');
        }
        
        ///Relation cart

        public function cart(){
            return $this->hasOne(Cart::class,'breeder_id');
        }
}
