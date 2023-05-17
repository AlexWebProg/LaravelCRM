<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\TechDoc;
use App\Models\User;
use App\Notifications\MailTechDocDeletedByAdmin;

class TechDocDeleteController extends Controller
{
    public function __invoke(User $client, TechDoc $tech_doc)
    {
        if (empty($tech_doc) || empty($client)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Документ или объект не найден'
                ]);
        }

        if (!empty($tech_doc->file)) {
            @unlink(storage_path('app/public/'.$tech_doc->file));
        }
        $tech_doc->delete();

        // Отправляем письмо заказчику
        if (!empty($client->is_active)) {
            $client->notify(new MailTechDocDeletedByAdmin([
                'tech_doc_name' => $tech_doc->name,
                'client_name' => $client->name,
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
