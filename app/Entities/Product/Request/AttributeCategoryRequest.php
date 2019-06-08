<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/7
 * Time: 22:21
 */

namespace App\Entities\Product\Request;


use App\Http\Requests\FormRequest;

class AttributeCategoryRequest extends FormRequest
{
    protected $rules = [
        'name' => 'required|min:2|max:100|unique:product_attribute_category',
        'pid' => 'required'
    ];

    public function rules()
    {
        if ($this->getMethod() == 'PUT') {
            return [
                //获取当前需要排除的id,这里的 product_attribute_type 是 路由 {} 中的参数，具体可用 php artisan route:list 查看
                'name' => 'min:2|max:100|unique:product_attribute_category,name,' . $this->route('product_attribute_type')
            ];
        }
        return $this->rules;
    }
}