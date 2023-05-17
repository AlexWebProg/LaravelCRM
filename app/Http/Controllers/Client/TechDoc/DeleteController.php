<?php

namespace App\Http\Controllers\Client\TechDoc;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Stat;
use App\Models\TechDoc;
use App\Notifications\MailTechDocDeleted;
use Illuminate\Support\Facades\Notification;

class DeleteController extends Controller
{
    public function __invoke(TechDoc $tech_doc)
    {
        // Не удаляем документы, загруженные более часа назад
        if ($tech_doc->created_at < date('Y-m-d H:i:s',(time() - 3600))) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Документ, вероятно, уже принят в работу.<br/>Для его удаления, пожалуйста, напишите в чат.'
                ]);
        }

        if (!empty($tech_doc->file)) {
            @unlink(storage_path('app/public/'.$tech_doc->file));
        }
        $tech_doc->delete();
        // Пишем статистику
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Тех. док.: удалён документ']);
        // Отправляем письмо сотруднику
        $manager_email = Settings::where('name','tech_doc_email')->first()->value;
        if (!empty($manager_email)) {
            Notification::route('mail', array_map('trim', explode(',', $manager_email)))->notify(new MailTechDocDeleted([
                'client_address' => auth()->user()->address,
                'client_name' => auth()->user()->name,
                'tech_doc_name' => $tech_doc->name,
                'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : 'нет',
                'tech_doc_url' => route('manager.client.edit', [auth()->user()->id,'tech_doc'])
            ]));
        }
        // Возвращаемся к странице технической документации
        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'success',
                'message' => 'Документ удалён'
            ]);
    }
}
