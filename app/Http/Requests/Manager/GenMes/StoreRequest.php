<?php

namespace App\Http\Requests\Manager\GenMes;

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
            'text' => 'nullable|string|required_without:add_message_files',
            'add_message_files.*' => 'nullable|file|required_without:text',
            'to_in_process_1' => 'nullable|in:1',
            'to_in_process_0' => 'nullable|in:1',
            'to_ob_status_2' => 'nullable|in:1',
        ];
    }

    public function messages()
    {
        return [
            'text.required_without' => 'Пожалуйста, введите текст сообщения, или загрузите файл(-ы)',
            'text.string' => 'Это поле должно быть текстом',
        ];
    }
}
