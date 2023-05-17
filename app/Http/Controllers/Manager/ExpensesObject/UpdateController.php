<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ExpensesObject\UpdateRequest;
use App\Models\ExpensesObject;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, ExpensesObject $expense)
    {
        $data = $request->validated();
        $expense->update($data);
        if (empty($data['client_id'])) {
            return redirect()
                ->route('manager.expenses_guarantee.index')
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Расход успешно сохранён'
                ]);
        } else {
            return redirect()
                ->route('manager.client.edit', [$data['client_id'],'expenses'])
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Расход успешно сохранён'
                ]);
        }
    }
}
