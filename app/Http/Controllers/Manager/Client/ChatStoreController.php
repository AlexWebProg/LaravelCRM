<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\ChatStoreRequest;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\MailChatMesCreatedByManager;
use App\Services\Common\UploadFileService;
use Illuminate\Support\Facades\Gate;

class ChatStoreController extends Controller
{
    public object $service;

    public function __construct(UploadFileService $service)
    {
        $this->service = $service;
    }

    public function __invoke(ChatStoreRequest $request)
    {
        $data = $request->validated();
        $data['manager_id'] = auth()->user()->id;
        if (!empty($data['add_message_files'])) {
            if (count($data['add_message_files'])) {
                $arFiles = $data['add_message_files'];
            }
            unset($data['add_message_files']);
        }

        if (!empty($data['edit_message_id'])) {
            // Редактирование сообщения
            $message = Chat::find($data['edit_message_id']);
            if (!empty($message) && Gate::allows('edit-chat', $message) && !empty($data['text'])) {
                $message->text = $data['text'];
                $message->edited_at = now();
                $message->save();
                // Меняем дату обновления чата
                User::where('id', $data['client_id'])->update(['chat_updated_at' => now()]);
                return response([],200);
            }
        } else {
            // Создание сообщения
            unset($data['edit_message_id']);
            $chat = Chat::create($data);
            if (!empty($chat->id)) {
                // Меняем дату обновления чата
                User::where('id', $data['client_id'])->update(['chat_updated_at' => now()]);
                // Отправляем заказчику письмо
                $client = User::find($data['client_id']);
                $client->notify(new MailChatMesCreatedByManager($client->name));
                // Загружаем файлы
                if (!empty($arFiles)) {
                    foreach ($arFiles as $file) {
                        $this->service->saveChatFile($file,$chat->id);
                    }
                }
                return response([],200);
            }
        }
        return response([],500);
    }
}
