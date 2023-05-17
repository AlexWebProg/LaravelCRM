<?php

namespace App\Http\Requests\Manager\Client;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'text' => 'sometimes|nullable|string',
            'files' => 'sometimes',
            'files.*' => 'file',
            'remember' => 'sometimes|nullable|integer',
            'responsible' => 'required',
            'responsible.*' => 'integer|exists:users,id,is_active,1,type,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Это поле должно быть заполнено',
            'name.string' => 'Это поле должно быть текстом',
            'name.max' => 'Слишком длинный текст',

            'text.string' => 'Это поле должно быть текстом',
            'remember.integer' => 'Это поле должно быть переключателем',

            'responsible.required' => 'Не выбран ответственный. Попробуйте обновить страницу',
            'responsible.*.integer' => 'Не выбран ответственный. Попробуйте обновить страницу',
            'responsible.*.exists' => 'Этот сотрудник не найден в системе',
        ];
    }

    // Помечаем, что это валидация формы создания задачи
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                $validator->errors()->add('task_store', '1');
            }
        });
    }
}
