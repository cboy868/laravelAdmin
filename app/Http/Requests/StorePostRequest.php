<?php

namespace App\Http\Requests;

class StorePostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:255|unique:post',
            'content' => 'required',
        ];
    }
}
