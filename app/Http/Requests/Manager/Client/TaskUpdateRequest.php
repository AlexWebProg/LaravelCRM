<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
            'editable' => 'required|integer',
            'name' => 'required|string|max:255',
            'text' => 'sometimes|nullable|string',
            'files' => 'sometimes',
            'files.*' => 'file',
            'responsible' => 'required_if:editable,1',
            'responsible.*' => 'integer|exists:users,id,is_active,1,type,1',
            'remember' => 'sometimes|nullable|integer',
            'closed' => 'sometimes|nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Эта задача не найдена в системе',

            'editable.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'editable.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',

            'name.required' => 'Это поле должно быть заполнено',
            'name.string' => 'Это поле должно быть текстом',
            'name.max' => 'Слишком длинный текст',

            'text.string' => 'Это поле должно быть текстом',

            'responsible.required' => 'Не выбран ответственный. Попробуйте обновить страницу',
            'responsible.*.integer' => 'Не выбран ответственный. Попробуйте обновить страницу',
            'responsible.*.exists' => 'Этот сотрудник не найден в системе',

            'remember.integer' => 'Это поле должно быть переключателем',
            'closed.integer' => 'Это поле должно быть переключателем',
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
