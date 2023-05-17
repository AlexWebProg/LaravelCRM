<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Http\Controllers\Controller;
use App\Models\ExpensesObject;

class DeleteController extends Controller
{
    public function __invoke(ExpensesObject $expense)
    {
        // Удаляем расход
        $expense->delete();

        if (empty($expense->client_id)) {
            return redirect()
                ->route('manager.expenses_guarantee.index')
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Расход успешно удалён'
                ]);
        } else {
            return redirect()
                ->route('manager.client.edit', [$expense->client_id,'expenses'])
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Расход успешно удалён'
                ]);
        }
    }
}
