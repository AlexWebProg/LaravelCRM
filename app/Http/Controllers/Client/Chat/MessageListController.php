<?php

namespace App\Http\Controllers\Client\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Stat;
use App\Models\User;
use App\Services\Common\ViewItemService;

class MessageListController extends Controller
{
    public object $service;

    public function __construct(ViewItemService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $messages = Chat::where('client_id', auth()->user()->id)
            ->orderBy('id')
            ->get();

        if (count($messages)) {
            foreach ($messages as $message) {
                // Отмечаем сообщения прочитанными
                $arViewedUsers = $message->viewed;
                if (empty($arViewedUsers[auth()->user()->id])) {
                    if (!empty($message->manager_id)) {
                        User::where('id', auth()->user()->id)->update(['chat_updated_at' => now()]);
                        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Прочитано сообщение в чате']);
                    }
                }
                $this->service->makeItemViewed($message);
                // Функции
                $this->service->makeChatMessageFunctionsList($message);
            }
        }

        return view('client.chat.message_list',compact('messages'));
    }
}
