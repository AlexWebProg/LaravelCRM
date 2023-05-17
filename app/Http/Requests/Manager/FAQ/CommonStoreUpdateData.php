<?php
namespace App\Http\Requests\Manager\FAQ;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort' => 'nullable|integer',
            'is_active' => 'required|integer',
        ];
    }

    protected function commonMessages()
    {
        return [
            'question.required' => 'Это поле необходимо заполнить',
            'question.string' => 'Это поле должно быть текстом',

            'answer.required' => 'Это поле необходимо заполнить',
            'answer.string' => 'Это поле должно быть текстом',

            'sort.integer' => 'Это поле должно быть числом',

            'is_active.required' => 'Не указана активность: активен или не отображается',
            'is_active.integer' => 'Не указана активность: активен или не отображается',

            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Этот контакт не найден в системе',
        ];
    }
}
