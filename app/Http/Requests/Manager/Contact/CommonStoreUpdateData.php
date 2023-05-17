<?php
namespace App\Http\Requests\Manager\Contact;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'job' => 'required|string',
            'name' => 'required|string',
            'about' => 'nullable|string',
            'sort' => 'nullable|integer',
            'is_active' => 'required|integer',
        ];
    }

    protected function commonMessages()
    {
        return [
            'job.required' => 'Это поле необходимо заполнить',
            'job.string' => 'Это поле должно быть текстом',

            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'phone.required' => 'Это поле необходимо заполнить',
            'phone.numeric' => 'Это поле должно содержать только цифры',
            'phone.unique' => 'Контакт с таким телефоном уже существует',

            'about.string' => 'Это поле должно быть текстом',

            'sort.integer' => 'Это поле должно быть числом',

            'is_active.required' => 'Не указана активность: активен или не отображается',
            'is_active.integer' => 'Не указана активность: активен или не отображается',

            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Этот контакт не найден в системе',
        ];
    }
}
