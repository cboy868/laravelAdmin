<?php

namespace App\Entities\Store;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends ActiveRecord
{
    protected $table = 'store_cost';

    /**
     * 所属商家
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
