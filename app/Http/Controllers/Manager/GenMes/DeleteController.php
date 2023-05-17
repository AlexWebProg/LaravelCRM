<?php

namespace App\Http\Controllers\Manager\GenMes;

use App\Models\Chat;
use App\Http\Controllers\Controller;
use App\Models\ChatFiles;
use App\Models\GenMes;
use App\Models\User;

class DeleteController extends Controller
{

    public function __invoke(GenMes $gen_mes)
    {
        // Удаляем файлы и сообщения из чата
        $messages = Chat::where('gen_mes_id',$gen_mes->id)->get();
        if (count($messages)) {
            foreach ($messages as $message) {
                if (!empty($message->message_files) && count($message->message_files)) {
                    foreach ($message->message_files as $chat_file) {
                        $chat_file->delete();
                        // Если файл не используется в других сообщениях, удаляем его
                        if (ChatFiles::where('src', $chat_file->src)->doesntExist()) {
                            @unlink(storage_path('app/public/'.$chat_file->src));
                            if (!empty($chat_file->preview)) {
                                @unlink(storage_path('app/public/'.$chat_file->preview));
                            }
                        }
                    }
                }
                User::where('id', $message->client_id)->update(['chat_updated_at' => now()]);
                $message->delete();
            }
        }

        // Удаляем сообщение из истории общих рассылок
        $gen_mes->delete();

        return redirect()
            ->route('manager.gen_mes.create')
            ->with('notification', [
                'class' => 'success',
                'message' => 'Сообщение изменено'
            ]);

    }
}
