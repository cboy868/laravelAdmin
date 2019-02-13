<?php

namespace App\Http\Requests;

class PostRequest extends FormRequest
{

    protected $rules = [
        'title' => 'required|min:3|max:255|unique:post',
        'content' => 'required',
    ];

    protected function validationData()
    {
        return $this->input('params');
    }

    public function rules()
    {
        $method = $this->input('method');
        $rules = $this->rules;

        // 根据不同的情况, 添加不同的验证规则
        if ($method == 'post.edit')//如果是save方法
        {
            $rules['id'] = 'required|integer';
            $rules['title'] = 'required|min:3|max:255|unique:post,title,' . $this->input('params.id');
        }

        if (in_array($method, ['post.delete', 'post.restore', 'post.show']))//如果是save方法
        {
            //只有这一个条件
            $rules = [
                'id' => 'required|integer'
            ];
        }

        return $rules;
    }
}
