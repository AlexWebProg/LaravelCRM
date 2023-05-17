<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\StoreRequest;
use App\Models\User;
use App\Notifications\MailClientCreated;
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
        $data['type'] = 0;
        $data['is_active'] = 1;
        $data['ob_status'] = 1;
        $user = User::firstOrCreate(['email' => $data['email']], $data);
        if (!empty($user->id)) {
            $user->notify(new MailClientCreated($email));
            return redirect()
                ->route('manager.client.edit', $user->id)
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый объект успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При создании нового объекта произошла неизвестная ошибка.<br/>Объект не был создан'
                ]);
        }

    }
}
