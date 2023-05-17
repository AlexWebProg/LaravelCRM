<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class EstimateUploadRequest extends FormRequest
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
            'file' => 'required|file|mimetypes:application/pdf',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Не выбран файл для загрузки',
            'file.file' => 'Не выбран файл для загрузки',
            'file.mimetypes' => 'Формат файла не поддерживается',
        ];
    }
}
