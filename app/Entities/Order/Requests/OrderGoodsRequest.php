<?php

namespace App\Entities\Order\Requests;

use App\Http\Requests\FormRequest;

/**
 *
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class OrderGoodsRequest extends FormRequest
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
