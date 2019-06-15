<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/6/15
 * Time: 14:06
 */

namespace App\Entities\Permission;

use App\Admin;
use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelRoles extends ActiveRecord
{
    protected $table = 'auth_model_has_roles';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'role_id',
        'model_id',
        'model_type'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(Admin::class, 'id', 'model_id');
    }
}
