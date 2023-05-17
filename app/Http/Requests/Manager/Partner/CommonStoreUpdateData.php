<?php
namespace App\Http\Requests\Manager\Partner;

use App\Rules\MobilePhone;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'site' => 'nullable|string|url',
            'name' => 'required|string',
            'about' => 'nullable|string',
            'sort' => 'nullable|integer',
            'is_active' => 'required|integer',
            'phone' => [
                'required',
                'numeric',
                new MobilePhone
            ],
        ];
    }

    protected function commonMessages()
    {
        return [
            'site.required' => 'Это поле необходимо заполнить',
            'site.string' => 'Это поле должно быть текстом',
            'site.url' => 'Адрес сайта заполнен неверно. Пожалуйста, скопируйте его в это поле из адресной строки браузера',

            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'phone.required' => 'Это поле необходимо заполнить',
            'phone.numeric' => 'Это поле должно содержать только цифры',

            'about.string' => 'Это поле должно быть текстом',

            'sort.integer' => 'Это поле должно быть числом',

            'is_active.required' => 'Не указана активность: активен или не отображается',
            'is_active.integer' => 'Не указана активность: активен или не отображается',

            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Этот партнёр не найден в системе',
        ];
    }
}
