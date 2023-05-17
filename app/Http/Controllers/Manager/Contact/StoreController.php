<?php

namespace App\Http\Controllers\Manager\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Contact\StoreRequest;
use App\Models\Contact;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $contact = Contact::firstOrCreate(['phone' => $data['phone']], $data);
        if (!empty($contact->id)) {
            return redirect()
                ->route('manager.contact.edit', $contact->id)
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый контакт успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При создании нового контакта произошла неизвестная ошибка.<br/>Контакт не был создан'
                ]);
        }

    }
}
