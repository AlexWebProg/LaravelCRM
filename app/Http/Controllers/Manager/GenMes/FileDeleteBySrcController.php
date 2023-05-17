<?php

namespace App\Http\Controllers\Manager\GenMes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\GenMes\FileDeleteBySrcRequest;
use App\Models\Chat;
use App\Models\ChatFiles;
use App\Models\GenMes;
use App\Models\User;

class FileDeleteBySrcController extends Controller
{
    public function __invoke(FileDeleteBySrcRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['src']) && !empty($data['gen_mes_id'])) {
            $gen_mes = GenMes::find($data['gen_mes_id']);
            $messages = Chat::where('gen_mes_id',$gen_mes->id)->get();
            if (!empty($messages) && count($messages)) {
                foreach ($messages as $message) {
                    $chat_file = ChatFiles::where('message_id',$message->id)->where('src', $data['src'])->first();
                    if (!empty($chat_file)) {
                        // Удаляем файл из каждого сообщения
                        $chat_file->delete();
                        User::where('id', $message->client_id)->update(['chat_updated_at' => now()]);
                        // Если файл не используется в других сообщениях, удаляем его
                        if (ChatFiles::where('src', $chat_file->src)->doesntExist()) {
                            @unlink(storage_path('app/public/' . $chat_file->src));
                            if (!empty($chat_file->preview)) {
                                @unlink(storage_path('app/public/' . $chat_file->preview));
                            }
                        }
                    }
                }
            }
            // Если больше нет файлов, отмечаем это
            if (!Chat::where('gen_mes_id',$gen_mes->id)->first()->message_files->count()) {
                $gen_mes->files = null;
                $gen_mes->save();
            }
            return response(['result' => 1], 200);
        }
        return response(['result'=> 0],200);
    }
}
