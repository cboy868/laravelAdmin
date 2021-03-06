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
        'title' => 'required|min:1|max:255|unique:novel',
        'type' => 'required',
        'summary' => 'max:500',
        'category_id' => 'integer',
        'author_id' => 'integer',
        'rate' => 'integer',
        'like' => 'integer',
        'words' => 'integer',
        'type' => 'integer'
    ];

    public function rules()
    {
        $method = $this->input('method');
        $rules = $this->rules;

        if (in_array($method, ['novel.show']))//如果是show方法
        {
            //只有这一个条件
            $rules = [
                'id' => 'required|integer'
            ];
        }

        return $rules;
    }
}
