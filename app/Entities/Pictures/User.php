<?php

namespace App\Entities\Pictures;

use App\Member;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 直接继承外部的总表
 * Class User
 * @package App\Entities\Pictures
 */
class User extends Member
{
    protected $table = 'users';

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pictures(): BelongsToMany
    {
        return $this->belongsToMany(Pictures::class,
            'pictures_user_rel',
            'user_id',
            'pictures_id'
        )->withPivot('pictures_id');
    }
}
