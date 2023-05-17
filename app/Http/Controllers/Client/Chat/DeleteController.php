<?php

namespace App\Http\Controllers\Client\Chat;

use App\Models\Chat;
use App\Http\Controllers\Controller;
use App\Models\ChatFiles;
use App\Models\Stat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeleteController extends Controller
{
    public function __invoke(Request $request)
    {
        $message_id = (int) $request->input('message_id');
        if (!empty($message_id)) $message = Chat::find($message_id);
        if (!empty($message) && Gate::allows('edit-chat', $message)) {
            $message->deleted_at = now();
            $message->save();
            // Удаляем файлы, прикреплённые к сообщению
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
            Stat::create(['user_id' => auth()->user()->id, 'action' => 'Удалено сообщение в чате']);
            // Меняем дату обновления чата
            User::where('id', auth()->user()->id)->update(['chat_updated_at' => now()]);
            return response([],200);
        }
        return response([],500);
    }

}
