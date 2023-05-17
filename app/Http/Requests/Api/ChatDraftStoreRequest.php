<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChatDraftStoreRequest extends FormRequest
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
            'manager_id' => 'sometimes|nullable|integer|exists:users,id,is_active,1,type,1',
            'reply_message_id' => 'nullable|integer|exists:chat,id',
            'edit_message_id' => 'nullable|integer|exists:chat,id',
            'text' => 'nullable|string',
        ];
    }

}
