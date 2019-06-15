<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/6/15
 * Time: 14:07
 */

namespace App\Entities\Permission;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RolePermissions extends ActiveRecord
{
    protected $table = 'auth_role_has_permissions';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id'
    ];


    public function permissions(): HasMany
    {
        return $this->hasMany(Permissions::class, 'id', 'permission_id');
    }
}
