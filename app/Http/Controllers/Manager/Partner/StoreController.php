<?php

namespace App\Http\Controllers\Manager\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Partner\StoreRequest;
use App\Models\Partner;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $partner = Partner::create($data);
        if (!empty($partner->id)) {
            return redirect()
                ->route('manager.partner.edit', $partner->id)
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый партнёр успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При создании нового партнёра произошла неизвестная ошибка.<br/>Партнёр не был создан'
                ]);
        }

    }
}
