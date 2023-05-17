<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\WebCamRequest;
use App\Models\User;
use App\Notifications\MailWebCamUpdated;

class WebCamController extends Controller
{
    public function __invoke(WebCamRequest $request, User $client)
    {
        $data = $request->validated();
        $client->update($data);
        // Отправляем письмо о добавлении веб-камеры
        if (!empty($data['webcam']) && !empty($client->is_active)) {
            $client->notify(new MailWebCamUpdated($client->name));
        }
        return redirect()
            ->route('manager.client.edit', [$client->id,'webcam'])
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
