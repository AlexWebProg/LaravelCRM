<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ExpensesObject\StoreRequest;
use App\Models\ExpensesObject;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['manager_id'] = auth()->user()->id;
        $expenses_object = ExpensesObject::create($data);
        if (empty($expenses_object)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'При добавлении расхода произошла неизвестная ошибка.<br/>Расход не был создан'
                ]);
        } elseif (empty($data['client_id'])) {
            return redirect()
                ->route('manager.expenses_guarantee.index')
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый расход успешно добавлен'
                ]);
        } else {
            return redirect()
                ->route('manager.client.edit', [$data['client_id'],'expenses'])
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Новый расход успешно добавлен'
                ]);
        }
    }
}
