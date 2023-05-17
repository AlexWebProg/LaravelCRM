<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\TaskUpdateRequest;
use App\Models\Task;
use App\Models\TaskFiles;
use App\Models\User;
use App\Services\Common\UploadFileService;
use App\Services\Manager\Client\Service;

class TaskUpdateController extends BaseController
{
    public object $file_service;

    public function __construct(Service $service, UploadFileService $file_service)
    {
        parent::__construct($service);
        $this->file_service = $file_service;
    }

    public function __invoke(User $client, TaskUpdateRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['files']) && count($data['files'])) {
            $arFiles = $data['files'];
            unset($data['files']);
        }
        $task = Task::find($data['id']);

        // Обновляем данные задачи
        if (!empty($data['closed']) && empty($task->closed_at)) {
            $data['closed_at'] = now();
            $data['manager_closed_id'] = auth()->user()->id;
        } elseif (empty($data['closed']) && !empty($task->closed_at)) {
            $data['closed_at'] = null;
            $data['manager_closed_id'] = null;
        }

        // Напоминаем ответственному
        $arRemember = [];
        if (empty($data['editable'])) $data['responsible'] = $task->responsible;
            if (!empty($data['responsible']) && count($data['responsible'])) {
            foreach ($data['responsible'] as $intResponsible) {
                $arRemember[$intResponsible] = 1;
            }
        }
        if (!empty($data['remember'])) $arRemember[auth()->user()->id] = 1; // Напоминаем себе, если установлена галка
        $data['remember'] = $arRemember;

        if (!empty($data['closed'])) {
            $data['remember'] = [];
            unset($data['closed']);
        }
        // Если задача не должна редактироваться сотрудником, оставляем только напоминание и завершение
        if (empty($data['editable'])) unset($data['name'],$data['text']);
        unset($data['id'],$data['editable']);
        $task->update($data);

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
                    'message' => 'Задача обновлена'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При обновлении задачи произошла неизвестная ошибка.<br/>Задача не была обновлена'
                ]);
        }

    }
}
