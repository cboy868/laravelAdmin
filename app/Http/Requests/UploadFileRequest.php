<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/21
 * Time: 15:26
 */

namespace App\Http\Requests;


class UploadFileRequest extends FormRequest
{

    /**
     * 如果 用这个，则传参数时，外面不包params
     * 否则要修改控制器上传方法
     * @return array|null|string
     */
    protected function validationData()
    {
        return $this->input();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'image',
            'folder' => 'required',
        ];
    }
}