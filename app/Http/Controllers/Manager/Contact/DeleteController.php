<?php

namespace App\Http\Controllers\Manager\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class DeleteController extends Controller
{
    public function __invoke(Contact $contact)
    {
        // Удаляем контакт
        $contact->delete();

        return redirect()
            ->route('manager.contact.index')
            ->with('notification',[
                'class' => 'success',
                'message' => 'Контакт удалён'
            ]);
    }
}
