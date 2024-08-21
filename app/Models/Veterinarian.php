<?php

namespace App\Models;

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Veterinarian extends Authenticatable implements JWTSubject
{
    use HasFactory,Notifiable,HasRoles,HasApiTokens;
    protected $fillable = [
        'name',
        'certificate_image',
        'experience_certificate_image',
        'role',
        'confirm_password',
        'email',
        'password'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    //casts image to array data
    protected $casts = [
        'experience_certificate_image' => 'array',
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
}
