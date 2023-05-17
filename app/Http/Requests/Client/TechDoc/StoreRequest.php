<?php

namespace App\Http\Requests\Client\TechDoc;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string',
            'comment' => 'nullable|string',
            'file' => 'required|file|mimetypes:application/pdf|max:20000',
        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'comment.string' => 'Это поле должно быть текстом',

            'file.required' => 'Не выбран файл для загрузки',
            'file.file' => 'Не выбран файл для загрузки',
            'file.mimetypes' => 'Формат файла не поддерживается',
            'file.max' => 'Размер файла не должен быть больше 20Мб',
        ];
    }

    // Помечаем, что это валидация формы загрузки файла
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                $validator->errors()->add('tech_doc_store', '1');
            }
        });
    }

}
