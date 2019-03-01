<?php

namespace App\Http\Requests;

class SmsCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'mobile' => 'required',
        ];
    }
}
