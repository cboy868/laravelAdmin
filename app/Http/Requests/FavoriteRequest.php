<?php

namespace App\Http\Requests;

/**
 * Class StoreCategoryRequest
 * @package App\Http\Requests
 */
class FavoriteRequest extends FormRequest
{
    protected $rules = [
        'id' => 'required',
    ];

    public function rules()
    {
        return $this->rules;
    }
}
