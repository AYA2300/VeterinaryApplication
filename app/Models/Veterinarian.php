<?php

namespace App\Models;

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class Veterinarian extends Authenticatable implements JWTSubject
{
    use HasFactory,Notifiable,HasRoles;
    protected $fillable = [
        'name',
        'university',
        'graduation_year',
        'profile_picture',
        'degree_certificate',
        'experience_certificate',
        'role',
        'email',
        'password'

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
