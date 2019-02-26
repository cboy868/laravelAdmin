<?php

namespace App\Entities\Novel\Requests;

use App\Http\Requests\FormRequest;

/**
 * 小说的内容应该不用编辑，如果想编辑，应该先好具体位置
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class SmsRequest extends FormRequest
{

    protected $rules = [
        'mobile' => 'required',
        'content' => 'required|min:10',
    ];

    public function rules()
    {
        $rules = $this->rules;

        return $rules;
    }
}
