<?php

namespace App;

use Overtrue\LaravelFollow\Traits\CanFavorite;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable implements IUser
{
    use Notifiable;
    use CanFavorite;

    const USER_LEVEL_NORMAL = 0;//普通用户
    const USER_LEVEL_ALL = 1;//全栈会员
    const USER_LEVEL_PICTURE = 2;//图集会员

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','level', 'mobile','login_times'
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

    public static function levels($level=null)
    {
        $levels = [
            self::USER_LEVEL_ALL => '全栈会员',
            self::USER_LEVEL_PICTURE => '图栈会员',
            self::USER_LEVEL_NORMAL => '普通会员'
        ];


        return $level === null ? $levels : $levels[$level];
    }


    public function getLevel()
    {
        return self::levels($this->level);
    }
}