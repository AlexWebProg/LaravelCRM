<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\TaskDeleteRequest;
use App\Models\Task;
use App\Models\TaskFiles;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TaskDeleteController extends BaseController
{
    public function __invoke(User $client, TaskDeleteRequest $request)
    {
        $data = $request->validated();
        $task = Task::find($data['id']);

        if (empty($task) || empty($client)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Задача или объект не найдены'
                ]);
        }

        // Удаляем файлы задачи
        if (!empty($task->task_files) && count($task->task_files)) {
            foreach ($task->task_files as $task_file) {
                $task_file->delete();
                @unlink(storage_path('app/public/'.$task_file->src));
            }
        }

        // Удаляем задачу
        $task->delete();

        // Обновляем напоминание о задачах у объекта
        $this->service->updateTaskRemember($client);

        // Возвращаемся к плану работ
        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'success',
                'message' => 'Задача удалена'
            ]);

    }
}
