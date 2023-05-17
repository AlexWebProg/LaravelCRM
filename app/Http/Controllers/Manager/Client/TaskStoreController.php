<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\TaskStoreRequest;
use App\Models\Task;
use App\Models\TaskFiles;
use App\Models\User;
use App\Services\Common\UploadFileService;
use App\Services\Manager\Client\Service;

class TaskStoreController extends BaseController
{
    public object $file_service;

    public function __construct(Service $service, UploadFileService $file_service)
    {
        parent::__construct($service);
        $this->file_service = $file_service;
    }

    public function __invoke(User $client, TaskStoreRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['files']) && count($data['files'])) {
            $arFiles = $data['files'];
            unset($data['files']);
        }

        // Сохраняем задачу
        $data['client_id'] = $client->id;
        $data['manager_created_id'] = auth()->user()->id;
        // Напоминаем ответственному
        $arRemember = [];
        if (!empty($data['responsible']) && count($data['responsible'])) {
            foreach ($data['responsible'] as $intResponsible) {
                $arRemember[$intResponsible] = 1;
            }
        }
        if (!empty($data['remember'])) $arRemember[auth()->user()->id] = 1; // Напоминаем себе, если установлена галка
        $data['remember'] = $arRemember;
        $task = Task::create($data);

        // Загружаем файлы
        if (!empty($task->id)) {
            if (!empty($arFiles)) {
                foreach ($arFiles as $file) {
                    TaskFiles::create([
                        'task_id' => $task->id,
                        'src' => $this->file_service->saveFile($file,'task'), // Видео в mov сохраняем в mp4, остальные файлы - как есть
                        'name' => $file->getClientOriginalName()
                    ]);
                }
            }
        }

        // Обновляем напоминание о задачах у объекта
        $this->service->updateTaskRemember($client);

        if (!empty($task->id)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новая задача успешно добавлена'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При создании новой задачи произошла неизвестная ошибка.<br/>Задача не была создана'
                ]);
        }

    }
}
