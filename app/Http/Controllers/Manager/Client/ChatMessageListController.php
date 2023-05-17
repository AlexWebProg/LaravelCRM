<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Services\Common\ViewItemService;

class ChatMessageListController extends Controller
{
    public object $service;

    public function __construct(ViewItemService $service)
    {
        $this->service = $service;
    }

    public function __invoke(User $client)
    {
        $messages = Chat::where('client_id', $client->id)
            ->orderBy('id')
            ->get();

        if (count($messages)) {
            foreach ($messages as $message) {
                // Прочтение пользователями
                $this->service->makeItemViewed($message);
                // Функции
                $this->service->makeChatMessageFunctionsList($message);
            }
        }

        return view('manager.client.edit.chat_message_list',compact('messages','client'));
    }
}
