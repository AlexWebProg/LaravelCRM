<?php

namespace App\Http\Requests\Client\Chat;

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
            'reply_message_id' => 'nullable|integer|exists:chat,id',
            'edit_message_id' => 'nullable|integer|exists:chat,id',
            'text' => 'nullable|string|required_without:add_message_files',
            'add_message_files.*' => 'nullable|file|required_without:text',
        ];
    }

    public function messages()
    {
        return [
            'reply_message_id.integer' => 'Что-то не так с ответом на сообщение. Попробуйте обновить страницу',
            'reply_message_id.exists' => 'Что-то не так с ответом на сообщение. Попробуйте обновить страницу',

            'edit_message_id.integer' => 'Что-то не так с редактированием сообщения. Попробуйте обновить страницу',
            'edit_message_id.exists' => 'Что-то не так с редактированием сообщения. Попробуйте обновить страницу',

            'text.required_without' => 'Это поле необходимо заполнить',
            'text.string' => 'Это поле должно быть текстом',
        ];
    }
}
