<?php

namespace App\Http\Controllers\Manager\GenMes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\GenMes\StoreRequest;
use App\Models\Chat;
use App\Models\ChatFiles;
use App\Models\GenMes;
use App\Models\User;
use App\Services\Common\UploadFileService;

class UpdateController extends Controller
{
    public object $service;

    public function __construct(UploadFileService $service)
    {
        $this->service = $service;
    }

    public function __invoke(StoreRequest $request, GenMes $gen_mes)
    {
        $data = $request->validated();

        // Обновляем сообщение в истории общих рассылок
        $gen_mes->text = $data['text'];
        if (!empty($data['add_message_files'])) {
            $gen_mes->files = 1;
        }
        $gen_mes->save();

        // Загружаем файлы
        $arFiles = [];
        if (!empty($data['add_message_files'])) {
            if (count($data['add_message_files'])) {
                foreach ($data['add_message_files'] as $file) {
                    $arFiles[] = $this->service->saveChatFile($file,null);
                }
            }
            unset($data['add_message_files']);
        }

        // Обновляем сообщение в чатах всех объектов
        $messages = Chat::where('gen_mes_id',$gen_mes->id)->get();
        if (!empty($messages) && count($messages)) {
            foreach ($messages as $message) {
                $message->text = $data['text'];
                $message->save();
                User::where('id', $message->client_id)->update(['chat_updated_at' => now()]);
                // Если есть файлы, прикрепляем их к сообщению
                if (count($arFiles)) {
                    foreach ($arFiles as $file) {
                        ChatFiles::create([
                            'message_id' => $message->id,
                            'src' => $file->src,
                            'name' => $file->name,
                            'type' => $file->type,
                            'width' => $file->width,
                            'height' => $file->height,
                            'preview' => $file->preview
                        ]);
                    }
                }
            }
        }


        // Если есть файлы, удаляем изначально сохранённые (без номера сообщения)
        if (count($arFiles)) {
            foreach ($arFiles as $file) {
                ChatFiles::where('id',$file->id)->delete();
            }
        }

        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'success',
                'message' => 'Сообщение изменено'
            ]);

    }
}
