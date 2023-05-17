<?php

namespace App\Http\Requests\Manager\Settings;

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
            'chat_new_client_mes_email' => 'required|string',
            'tech_doc_email' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'chat_new_client_mes_email.required' => 'Это поле необходимо заполнить',
            'chat_new_client_mes_email.string' => 'Это поле должно быть текстом',

            'tech_doc_email.required' => 'Это поле необходимо заполнить',
            'tech_doc_email.string' => 'Это поле должно быть текстом',
        ];
    }
}
