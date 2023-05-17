<?php
namespace App\Http\Requests\Manager\Client;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'address' => 'required|string',
            'name' => 'required|string',
            'coordinates' => 'nullable|string',
        ];
    }

    protected function commonMessages()
    {
        return [
            'address.required' => 'Это поле необходимо заполнить',
            'address.string' => 'Это поле должно быть текстом',

            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'coordinates.string' => 'Что-то не так с координатами. Пожалуйста, скопируйте их из Яндекс Карты',

            'email.required' => 'Это поле необходимо заполнить',
            'email.email' => 'Это поле не похоже на адрес электронной почты',
            'email.unique' => 'Пользователь с таким email уже существует',

            'phone.numeric' => 'Это поле должно содержать только цифры',
            'phone.unique' => 'Пользователь с таким телефоном уже существует',

            'ob_status.required' => 'Не указан статус: в работе, на гарантии, завершён',
            'ob_status.integer' => 'Не указан статус: в работе, на гарантии, завершён',

            'in_process.required' => 'Не указано значение параметра "Работа начата": да или нет',
            'in_process.integer' => 'Не указано значение параметра "Работа начата": да или нет',

            'id.required' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.integer' => 'Что-то пошло не так. Попробуйте обновить страницу',
            'id.exists' => 'Этот объект не найден в системе',
        ];
    }
}
