<?php

namespace App\Entities\Novel\Requests;

use App\Http\Requests\FormRequest;

/**
 * 小说的内容应该不用编辑，如果想编辑，应该先好具体位置
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class NovelRequest extends FormRequest
{

    protected $rules = [
    ];

    public function rules()
    {
        $method = $this->input('method');
        $rules = $this->rules;

        return $rules;
    }
}
