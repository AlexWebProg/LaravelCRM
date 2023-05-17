<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\User;

class ChatRememberController extends Controller
{

    public function __invoke(User $client, int $remember=0)
    {
        $arChatRemember = $client->chat_remember;
        if (!empty($remember) && empty($arChatRemember[auth()->user()->id])) {
            $arChatRemember[auth()->user()->id] = 1;
            $client->update(['chat_remember' => $arChatRemember]);
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Напоминание установлено'
                ]);
        } elseif (empty($remember) && !empty($arChatRemember[auth()->user()->id])) {
            unset($arChatRemember[auth()->user()->id]);
            $client->update(['chat_remember' => $arChatRemember]);
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Напоминание отменено'
                ]);
        }
        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'info',
                'message' => 'Изменений не выполнено'
            ]);
    }
}
