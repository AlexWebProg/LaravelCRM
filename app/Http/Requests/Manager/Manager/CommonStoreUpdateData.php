<?php
namespace App\Http\Requests\Manager\Manager;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'name' => 'required|string',
            'is_admin' => 'required|integer'
        ];
    }

    protected function commonMessages()
    {
        return [
            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'email.required' => 'Это поле необходимо заполнить',
            'email.email' => 'Это поле не похоже на адрес электронной почты',
            'email.unique' => 'Пользователь с таким email уже существует',

            'phone.numeric' => 'Это поле должно содержать только цифры',
            'phone.unique' => 'Пользователь с таким телефоном уже существует',

            'is_admin.required' => 'Не указан тип: руководитель или сотрудник. Попробуйте создать пользователя заново',
            'is_admin.integer' => 'Не указан тип: руководитель или сотрудник. Попробуйте создать пользователя заново',
        ];
    }
}
