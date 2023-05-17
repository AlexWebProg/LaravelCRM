<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class TechDocUpdateRequest extends FormRequest
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
            'edit_id' => 'required|integer',
            'edit_name' => 'required|string',
            'edit_comment' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'edit_id.required' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
            'edit_id.integer' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',

            'edit_name.required' => 'Это поле необходимо заполнить',
            'edit_name.string' => 'Это поле должно быть текстом',

            'comment.string' => 'Это поле должно быть текстом',
        ];
    }

    // Помечаем, что это валидация формы обновления документа
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                $validator->errors()->add('tech_doc_update', '1');
            }
        });
    }
}
