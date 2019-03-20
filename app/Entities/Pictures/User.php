<?php

namespace App\Entities\Pictures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
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
        );
    }
}
