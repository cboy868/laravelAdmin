<?php

namespace App\Entities\Permission\Requests;

use App\Http\Requests\FormRequest;

/**
 * 小说的内容应该不用编辑，如果想编辑，应该先好具体位置
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class RolePermissionRequest extends FormRequest
{
    protected $rules = [
        'role_id' => 'required',
        'permission_id' => 'required'
    ];

    public function rules()
    {
        return $this->rules;
    }
}
