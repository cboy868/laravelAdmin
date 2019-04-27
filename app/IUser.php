<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Spatie\Permission\Traits\HasRoles;

interface IUser extends \Tymon\JWTAuth\Contracts\JWTSubject
{

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier();


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    function getJWTCustomClaims();
}
