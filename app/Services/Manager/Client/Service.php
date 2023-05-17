<?php

namespace App\Services\Manager\Client;

use App\Models\User;

class Service
{
    // Обновляет у объекта признак напоминания о задачах сотруднику
    public function updateTaskRemember(User $client){
        $arTaskRemember = [];
        if (count($client->active_tasks)) {
            foreach ($client->active_tasks as $task) {
                if (!empty($task->remember) && count($task->remember)) {
                    foreach ($task->remember as $manager_id => $exist) {
                        $arTaskRemember[$manager_id] = 1;
                    }
                }
            }
        }
        $client->update(['task_remember' => $arTaskRemember]);
    }
}
