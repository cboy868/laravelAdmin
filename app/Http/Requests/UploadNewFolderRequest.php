<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/21
 * Time: 15:28
 */

namespace App\Http\Requests;

class UploadNewFolderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folder' => 'required',
            'new_folder' => 'required',
        ];
    }
}