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
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelPermissions extends ActiveRecord
{
    protected $table = 'auth_model_has_permissions';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'permission_id',
        'model_id',
        'model_type'
    ];

}
