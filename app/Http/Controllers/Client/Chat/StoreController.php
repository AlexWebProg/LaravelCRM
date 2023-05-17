<?php

namespace App\Http\Controllers\Client\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Chat\StoreRequest;
use App\Models\Chat;
use App\Models\Settings;
use App\Models\Stat;
use App\Models\User;
use App\Notifications\MailChatMesCreatedByClient;
use App\Services\Common\UploadFileService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class StoreController extends Controller
{
    public object $service;

    public function __construct(UploadFileService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = auth()->user()->id;
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
                Stat::create(['user_id' => auth()->user()->id, 'action' => 'Изменено сообщение в чате']);
                // Меняем дату обновления чата
                User::where('id', auth()->user()->id)->update(['chat_updated_at' => now()]);
                return response([],200);
            }
        } else {
            // Создание сообщения
            unset($data['edit_message_id']);
            $chat = Chat::create($data);
            if (!empty($chat->id)) {
                Stat::create(['user_id' => auth()->user()->id, 'action' => 'Отправлено сообщение в чате']);
                // Меняем дату обновления чата
                User::where('id', auth()->user()->id)->update(['chat_updated_at' => now()]);
                // Отправляем письмо сотруднику
                $manager_email = Settings::where('name','chat_new_client_mes_email')->first()->value;
                if (!empty($manager_email)) {
                    Notification::route('mail', array_map('trim', explode(',', $manager_email)))->notify(new MailChatMesCreatedByClient([
                        'client_address' => auth()->user()->address,
                        'client_name' => auth()->user()->name,
                        'chat_url' => route('manager.client.edit', [auth()->user()->id,'chat'])
                    ]));
                }
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
