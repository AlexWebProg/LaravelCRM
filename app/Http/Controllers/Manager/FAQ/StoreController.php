<?php

namespace App\Http\Controllers\Manager\FAQ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\FAQ\StoreRequest;
use App\Models\FAQ;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $faq = FAQ::create($data);
        if (!empty($faq->id)) {
            return redirect()
                ->route('manager.faq.edit', $faq->id)
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый вопрос успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При создании нового вопроса произошла неизвестная ошибка.<br/>Вопрос не был создан'
                ]);
        }

    }
}
