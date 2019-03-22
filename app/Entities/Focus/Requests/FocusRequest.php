<?php

namespace App\Entities\Focus\Requests;

use App\Http\Requests\FormRequest;

/**
 *
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class FocusRequest extends FormRequest
{
    protected $rules = [
        'name' => 'required|min:1|max:255',
    ];

    public function rules()
    {
        return $this->rules;
    }
}
