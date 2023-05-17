<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\TechDocStoreRequest;
use App\Models\Settings;
use App\Models\TechDoc;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\MailTechDocCreatedByManager;
use App\Notifications\MailTechDocCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TechDocStoreController extends Controller
{
    public function __invoke(User $client, TechDocStoreRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = $client->id;
        $data['uploaded_manager_id'] = auth()->user()->id;
        // Загружаем файл
        $data['file'] = Storage::disk('public')->put('/tech_doc', $data['file']);
        // Сохраняем документ в БД
        $tech_doc = TechDoc::create($data);
        if (!empty($tech_doc->id)) {
            // Отправляем письмо заказчику
            $client->notify(new MailTechDocCreatedByManager([
                'client_name' => $client->name,
                'tech_doc_name' => $tech_doc->name,
                'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : ''
            ]));
            // Отправляем письмо сотруднику
            $manager_email = Settings::where('name','tech_doc_email')->first()->value;
            Notification::route('mail', array_map('trim', explode(',', $manager_email)))->notify(new MailTechDocCreated([
                'client_address' => $client->address,
                'client_name' => $client->name,
                'tech_doc_name' => $tech_doc->name,
                'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : 'нет',
                'tech_doc_url' => route('manager.client.edit', [$client->id, 'tech_doc']),
                'uploaded_name' => auth()->user()->name
            ]));
            // Возвращаемся к странице технической документации
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Документ загружен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При загрузке документа произошла неизвестная ошибка.<br/>Документ не был загружен'
                ]);
        }
    }
}
