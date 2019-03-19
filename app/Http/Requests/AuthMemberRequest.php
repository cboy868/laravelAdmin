<?php

namespace App\Http\Requests;


class AuthMemberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => 'required',
            'code' => 'required'
        ];
    }
}
