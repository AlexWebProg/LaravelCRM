<?php

namespace App\Http\Controllers\Client\TechDoc;

use App\Http\Requests\Client\TechDoc\UpdateRequest;
use App\Models\Settings;
use App\Models\Stat;
use App\Models\TechDoc;
use App\Http\Controllers\Controller;
use App\Notifications\MailTechDocUpdated;
use Illuminate\Support\Facades\Notification;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();
        $tech_doc = TechDoc::find($data['edit_id']);
        $tech_doc->name = $data['edit_name'];
        $tech_doc->comment = empty($data['edit_comment']) ? null : $data['edit_comment'];
        $tech_doc->save();
        if ($tech_doc->wasChanged()) {
            // Пишем статистику
            Stat::create(['user_id' => auth()->user()->id, 'action' => 'Тех. док.: изменён документ']);
            // Отправляем письмо сотруднику
            $manager_email = Settings::where('name','tech_doc_email')->first()->value;
            if (!empty($manager_email)) {
                Notification::route('mail', array_map('trim', explode(',', $manager_email)))->notify(new MailTechDocUpdated([
                    'client_address' => auth()->user()->address,
                    'client_name' => auth()->user()->name,
                    'tech_doc_name' => $tech_doc->name,
                    'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : 'нет',
                    'tech_doc_url' => route('manager.client.edit', [auth()->user()->id,'tech_doc']),
                    'updated_name' => 'Заказчик'
                ]));
            }
        }

        // Возвращаемся к странице технической документации
        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'success',
                'message' => 'Изменения сохранены'
            ]);
    }
}
