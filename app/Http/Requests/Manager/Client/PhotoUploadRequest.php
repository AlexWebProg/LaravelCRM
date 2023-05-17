<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class PhotoUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file|mimetypes:video/*,image/*',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Не выбраны фото для загрузки',
            'file.file' => 'Не выбраны фото для загрузки',
            'file.mimetypes' => 'Формат файла не поддерживается',
        ];
    }
}
