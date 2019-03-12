<?php

namespace App\Entities\Goods\Requests;

use App\Http\Requests\FormRequest;

/**
 * 小说的内容应该不用编辑，如果想编辑，应该先好具体位置
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class TypeRequest extends FormRequest
{
    protected $rules = [
        'name' => 'required|min:1|max:255|unique:goods_type',
    ];

    public function rules()
    {
        if (request()->getMethod() == self::METHOD_PUT) {
            $this->rules['name'] .= ',name,'.$this->route('type');
        }

        return $this->rules;
    }
}
