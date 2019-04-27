<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends \App\User implements MustVerifyEmailContract
{
    use MustVerifyEmail, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    /**
     * Return the user's posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }


    /**
     * Return the user's likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'author_id');
    }
}
