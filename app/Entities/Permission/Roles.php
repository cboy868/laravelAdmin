<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/6/15
 * Time: 14:06
 */

namespace App\Entities\Permission;

use App\Entities\ActiveRecord;

class Roles extends ActiveRecord
{
    protected $table = 'auth_roles';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'guard_name',
        'created_at',
        'updated_at'
    ];
}
