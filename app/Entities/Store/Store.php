<?php

namespace App\Entities\Store;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends ActiveRecord
{
    protected $table = 'store';

    /**
     * 所属等级
     * @return BelongsTo
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * 费用
     * @return HasMany
     */
    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class, 'store_id');
    }
}
