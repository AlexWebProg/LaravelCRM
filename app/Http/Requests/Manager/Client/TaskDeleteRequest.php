<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class TaskDeleteRequest extends FormRequest
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
            'id' => 'required|integer|exists:task,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Эта задача не найдена в системе',
        ];
    }

    // Помечаем, что это валидация формы создания задачи
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                $validator->errors()->add('task_update', '1');
            }
        });
    }
}
