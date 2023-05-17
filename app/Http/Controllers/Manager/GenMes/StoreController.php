<?php

namespace App\Http\Controllers\Manager\GenMes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\GenMes\StoreRequest;
use App\Models\Chat;
use App\Models\ChatFiles;
use App\Models\GenMes;
use App\Models\User;
use App\Services\Common\UploadFileService;
use Illuminate\Support\Facades\Gate;

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
        $data['manager_id'] = auth()->user()->id;

        if (empty($data['to_ob_status_2']) && empty($data['to_in_process_1']) && empty($data['to_in_process_0'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Пожалуйста, выберите, кому отправлять сообщение'
                ]);
        }

        // Выбираем заказчиков, которым будет отправлено сообщение
        $clients = User::where('is_active', 1)
            ->where('type', 0)
            ->where('id', '!=', config('global.intDemoObject')) // Не будем создавать сообщение в чате демо-объекта
            ->get();
        if (!empty($clients) && count($clients)) {
            foreach ($clients as $k => $client) {
                if (
                    (empty($data['to_ob_status_2']) && $client->ob_status === 2) ||
                    (empty($data['to_in_process_1']) && $client->ob_status === 1 && $client->in_process === 1) ||
                    (empty($data['to_in_process_0']) && $client->ob_status === 1 && $client->in_process === 0)
                ) {
                    unset($clients[$k]);
                }
            }
        }

        if (empty($clients) || !count($clients)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Не найден ни один объект, подходящий для отправки сообщения'
                ]);
        }

        // Сохраняем сообщение в истории общих рассылок
        $gen_mes = GenMes::create([
            'text' => $data['text'],
            'files' => !empty($data['add_message_files']) ? 1 : null,
            'manager_id' => $data['manager_id'],
            'to_ob_status_2' => !empty($data['to_ob_status_2']) ? 1 : null,
            'to_in_process_1' => !empty($data['to_in_process_1']) ? 1 : null,
            'to_in_process_0' => !empty($data['to_in_process_0']) ? 1 : null,
        ]);
        $data['gen_mes_id'] = $gen_mes->id;
        unset($data['to_ob_status_2'],$data['to_in_process_1'],$data['to_in_process_0']);

        // Сразу же отметим сообщение прочитанным всеми сотрудниками
        $arViewedUsers = [];
        $dateViewed = date('d.m.Y H:i',time());
        $managers = User::where('is_active', 1)
            ->where('type', 1)
            ->orderBy('name')
            ->get();
        if (!empty($managers) && count($managers)) {
            foreach ($managers as $manager) {
                $arViewedUsers[$manager->id] = [
                    'name' => $manager->name,
                    'date' => $dateViewed,
                ];
            }
        }
        $data['viewed'] = $arViewedUsers;
        unset($dateViewed,$arViewedUsers);

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

        // Отправляем сообщение в чат всех выбранных объектов
        if (!empty($clients) && count($clients)) {
            foreach ($clients as $client) {
                $data['client_id'] = $client->id;
                $message = Chat::create($data);
                if (!empty($message->id)) {
                    User::where('id', $client->id)->update(['chat_updated_at' => now()]);
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
                unset($message);
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
                'message' => 'Сообщение отправлено'
            ]);

    }
}
