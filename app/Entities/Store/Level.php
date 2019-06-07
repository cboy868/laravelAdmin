<?php

namespace App\Entities\Store;


use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends ActiveRecord
{
    protected $table = 'store_level';

    /**
     * 有哪些商家
     * @return HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'level_id');
    }
}
