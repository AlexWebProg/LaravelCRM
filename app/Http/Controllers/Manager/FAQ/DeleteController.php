<?php

namespace App\Http\Controllers\Manager\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;

class DeleteController extends Controller
{
    public function __invoke(FAQ $faq)
    {
        // Удаляем контакт
        $faq->delete();

        return redirect()
            ->route('manager.faq.index')
            ->with('notification',[
                'class' => 'success',
                'message' => 'Вопрос удалён'
            ]);
    }
}
