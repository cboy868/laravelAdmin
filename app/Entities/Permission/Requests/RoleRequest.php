<?php

namespace App\Entities\Permission\Requests;

use App\Http\Requests\FormRequest;

/**
 * 小说的内容应该不用编辑，如果想编辑，应该先好具体位置
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class RoleRequest extends FormRequest
{
    protected $rules = [
        'name' => 'required|min:2|max:100|unique:auth_roles',
        'title' => 'required'
    ];

    public function rules()
    {
        if ($this->getMethod() == 'PUT') {
            return [
                'name' => 'min:2|max:100|unique:auth_roles,name,' . $this->route('role')
            ];
        }
        return $this->rules;
    }
}
