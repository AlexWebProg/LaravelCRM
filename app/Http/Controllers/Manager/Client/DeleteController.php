<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatDraft;
use App\Models\ChatFiles;
use App\Models\Estimate;
use App\Models\MasterEstimate;
use App\Models\EstimateComment;
use App\Models\MasterEstimateComment;
use App\Models\Photo;
use App\Models\Stat;
use App\Models\Task;
use App\Models\TechDoc;
use App\Models\User;

class DeleteController extends Controller
{
    public function __invoke(User $client)
    {
        // Удаляем объект
        $client->delete();

        // Удаляем фото
        $photos = Photo::where('client_id', $client->id)->get();
        if (count($photos)) {
            foreach ($photos as $photo) {
                @unlink(storage_path('app/public/'.$photo->src));
                $photo->delete();
            }
        }

        // Удаляем статистику
        Stat::where('user_id', $client->id)->delete();

        // Удаляем файлы из чата
        $chat = Chat::where('client_id', $client->id)->get();
        if (count($chat)) {
            foreach ($chat as $message) {
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
            }
        }

        // Удаляем чат
        Chat::where('client_id', $client->id)->delete();

        // Удаляем черновики чата
        ChatDraft::where('client_id', $client->id)->delete();

        // Удаляем техническую документацию
        $tech_docs = TechDoc::where('client_id', $client->id)->get();
        if (count($tech_docs)) {
            foreach ($tech_docs as $tech_doc) {
                @unlink(storage_path('app/public/'.$tech_doc->file));
                $tech_doc->delete();
            }
        }

        // Удаляем сметы
        $estimates = Estimate::where('client_id', $client->id)->get();
        if (count($estimates)) {
            foreach ($estimates as $estimate) {
                @unlink(storage_path('app/public/'.$estimate->src));
                $estimate->delete();
            }
        }

        // Удаляем комментарий к сметам
        EstimateComment::where('client_id', $client->id)->delete();

        // Удаляем сметы для мастера
        $master_estimates = MasterEstimate::where('client_id', $client->id)->get();
        if (count($master_estimates)) {
            foreach ($master_estimates as $master_estimate) {
                @unlink(storage_path('app/public/'.$master_estimate->src));
                $master_estimate->delete();
            }
        }

        // Удаляем комментарий к сметам для мастера
        MasterEstimateComment::where('client_id', $client->id)->delete();

        // Удаляем файлы из задач
        $tasks = Task::where('client_id', $client->id)->get();
        if (count($tasks)) {
            foreach ($tasks as $task) {
                if (!empty($task->task_files) && count($task->task_files)) {
                    foreach ($task->task_files as $task_file) {
                        $task_file->delete();
                        @unlink(storage_path('app/public/'.$task_file->src));
                    }
                }
            }
        }

        // Удаляем задачи
        Task::where('client_id', $client->id)->delete();

        return redirect()
            ->route('manager.client.index', $client->ob_status)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Объект удалён'
            ]);
    }
}
