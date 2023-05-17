<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class ChatStoreRequest extends FormRequest
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
            'client_id' => 'required|integer|exists:users,id,is_active,1,type,0',
            'reply_message_id' => 'nullable|integer|exists:chat,id',
            'edit_message_id' => 'nullable|integer|exists:chat,id',
            'text' => 'nullable|string|required_without:add_message_files',
            'add_message_files.*' => 'nullable|file|required_without:text',
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'Что-то не так с заказчиком. Попробуйте обновить страницу',
            'client_id.integer' => 'Что-то не так с заказчиком. Попробуйте обновить страницу',
            'client_id.exists' => 'Отправлять сообщения в чате можно только активным заказчикам',

            'reply_message_id.integer' => 'Что-то не так с ответом на сообщение. Попробуйте обновить страницу',
            'reply_message_id.exists' => 'Что-то не так с ответом на сообщение. Попробуйте обновить страницу',

            'edit_message_id.integer' => 'Что-то не так с редактированием сообщения. Попробуйте обновить страницу',
            'edit_message_id.exists' => 'Что-то не так с редактированием сообщения. Попробуйте обновить страницу',

            'text.required_without' => 'Это поле необходимо заполнить',
            'text.string' => 'Это поле должно быть текстом',
        ];
    }
}
