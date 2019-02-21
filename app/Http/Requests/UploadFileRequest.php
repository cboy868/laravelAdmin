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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required',
            'folder' => 'required',
        ];
    }
}