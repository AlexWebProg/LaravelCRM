<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class PhotoUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:photo,id',
            'comment' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
            'id.integer' => 'Произошла неизвестная ошибка. Попробуйте обновить страницу',
            'id.exists' => 'Фото не обнаружено в системе. Попробуйте обновить страницу',

            'comment.string' => 'Это поле должно быть текстом',
        ];
    }

    // Помечаем, что это валидация формы обновления документа
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                $validator->errors()->add('photo_update', '1');
            }
        });
    }
}
