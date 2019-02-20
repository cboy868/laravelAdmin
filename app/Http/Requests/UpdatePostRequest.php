<?php

namespace App\Http\Requests;

class UpdatePostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'min:3|max:255|unique:post,title,'. $this->route('post'),
        ];
    }
}
