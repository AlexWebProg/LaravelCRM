<?php

namespace App\Http\Controllers\Manager\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Contact\UpdateRequest;
use App\Models\Contact;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Contact $contact)
    {
        $data = $request->validated();
        $contact->update($data);
        return redirect()
            ->route('manager.contact.edit', $contact->id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
