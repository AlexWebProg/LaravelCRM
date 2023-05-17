<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\TechDocUpdateRequest;
use App\Models\Settings;
use App\Models\TechDoc;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\MailTechDocUpdatedByManager;
use App\Notifications\MailTechDocUpdated;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class TechDocUpdateController extends Controller
{
    public function __invoke(User $client, TechDocUpdateRequest $request)
    {
        $data = $request->validated();
        $tech_doc = TechDoc::find($data['edit_id']);
        // Проверяем, может ли сотрудник редактировать этот тех док
        if (!Gate::allows('edit_tech_doc', $tech_doc)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Вы можете редактировать только загруженные Вами документы'
                ]);
        }
        $tech_doc->name = $data['edit_name'];
        $tech_doc->comment = empty($data['edit_comment']) ? null : $data['edit_comment'];
        $tech_doc->save();
        if ($tech_doc->wasChanged()) {
            // Отправляем письмо заказчику
            $client->notify(new MailTechDocUpdatedByManager([
                'client_name' => $client->name,
                'tech_doc_name' => $tech_doc->name,
                'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : ''
            ]));
            // Отправляем письмо сотруднику
            $manager_email = Settings::where('name','tech_doc_email')->first()->value;
            if (!empty($manager_email)) {
                Notification::route('mail', array_map('trim', explode(',', $manager_email)))->notify(new MailTechDocUpdated([
                    'client_address' => $client->address,
                    'client_name' => $client->name,
                    'tech_doc_name' => $tech_doc->name,
                    'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : 'нет',
                    'tech_doc_url' => route('manager.client.edit', [$client->id,'tech_doc']),
                    'updated_name' => auth()->user()->name
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
