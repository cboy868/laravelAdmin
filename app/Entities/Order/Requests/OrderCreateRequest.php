<?php

namespace App\Entities\Order\Requests;

use App\Http\Requests\FormRequest;

/**
 *
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class OrderCreateRequest extends FormRequest
{

    protected $rules = [
        'goods_id' => 'required',
    ];

    public function rules()
    {
        $rules = $this->rules;

        return $rules;
    }
}
