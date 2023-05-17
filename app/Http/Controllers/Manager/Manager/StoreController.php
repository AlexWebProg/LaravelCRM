<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Http\Requests\Manager\Manager\StoreRequest;
use App\Models\User;
use App\Notifications\MailManagerCreated;
use Illuminate\Support\Facades\Hash;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = mt_rand(1000,9999);
        $email = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ];
        $data['password'] = Hash::make($data['password']);
        $data['type'] = 1;
        $data['is_active'] = 1;
        $user = User::firstOrCreate(['email' => $data['email']], $data);
        if (!empty($user->id)) {
            $user->notify(new MailManagerCreated($email));
            return redirect()
                ->route('manager.manager.edit', $user->id)
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый пользователь успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При создании нового пользователя произошла неизвестная ошибка.<br/>Пользователь не был создан'
                ]);
        }

    }
}
