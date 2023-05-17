<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class WebCamRequest extends FormRequest
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

    public function rules()
    {
        return [
            'webcam' => 'sometimes|nullable|string|max:255|url',
            'id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'webcam.string' => 'Это поле должно быть текстом',
            'webcam.max' => 'Слишком длинный адрес ссылки. Если, действительно, нужно использовать такие длинные ссылки, обратитесь к разработчику для реализации такой возможности',
            'webcam.url' => 'Ссылка введена некорректно. Проверьте её',

            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Этот объект не найден в системе',
        ];
    }
}
