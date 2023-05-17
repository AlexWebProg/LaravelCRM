<?php

namespace App\Http\Requests\Manager\ClientSummary;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'client' => 'required|integer|exists:users,id',
            'name' => 'required|string',
            'value' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'client.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'client.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'client.exists' => 'Этот объект не найден в системе',

            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'value.string' => 'Это поле должно быть текстом',
        ];
    }
}
