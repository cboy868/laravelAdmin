<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements \Tymon\JWTAuth\Contracts\JWTSubject
{
    use Notifiable;
    use HasRoles;
    use CanFavorite;

    const USER_LEVEL_NORMAL = 0;//普通用户
    const USER_LEVEL_ALL = 1;//全栈会员
    const USER_LEVEL_PICTURE = 2;//图集会员

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();// Eloquent Model method
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
