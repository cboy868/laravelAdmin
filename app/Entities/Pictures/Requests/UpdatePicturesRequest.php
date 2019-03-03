<?php

namespace App\Entities\Pictures\Requests;

use App\Http\Requests\FormRequest;

/**
 * 小说的内容应该不用编辑，如果想编辑，应该先好具体位置
 * Class NovelRequest
 * @package App\Entities\Novel\Requests
 */
class UpdatePicturesRequest extends FormRequest
{
    public function rules()
    {
        return [
        ];
    }
}
