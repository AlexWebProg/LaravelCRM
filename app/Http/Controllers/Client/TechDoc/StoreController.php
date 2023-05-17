<?php

namespace App\Http\Controllers\Client\TechDoc;

use App\Http\Requests\Client\TechDoc\StoreRequest;
use App\Models\Settings;
use App\Models\Stat;
use App\Models\TechDoc;
use App\Http\Controllers\Controller;
use App\Notifications\MailTechDocCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = auth()->user()->id;
        // Загружаем файл
        $data['file'] = Storage::disk('public')->put('/tech_doc', $data['file']);
        // Сохраняем документ в БД
        $tech_doc = TechDoc::create($data);
        if (!empty($tech_doc->id)) {
            // Пишем статистику
            Stat::create(['user_id' => auth()->user()->id, 'action' => 'Тех. док.: загружен документ']);
            // Отправляем письмо сотруднику
            $manager_email = Settings::where('name','tech_doc_email')->first()->value;
            if (!empty($manager_email)) {
                Notification::route('mail', array_map('trim', explode(',', $manager_email)))->notify(new MailTechDocCreated([
                    'client_address' => auth()->user()->address,
                    'client_name' => auth()->user()->name,
                    'tech_doc_name' => $tech_doc->name,
                    'tech_doc_comment' => !empty($tech_doc->comment) ? $tech_doc->comment : 'нет',
                    'tech_doc_url' => route('manager.client.edit', [auth()->user()->id,'tech_doc']),
                    'uploaded_name' => 'Заказчик'
                ]));
            }
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
