<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;

use App\Http\Requests\Manager\ExpensesPersonal\DeleteRequest;
use App\Models\ExpensesPersonal;

class DeleteController extends BaseController
{
    public function __invoke(ExpensesPersonal $expense, DeleteRequest $request)
    {
        $data = $request->validated();
        $expense->delete();

        // Если это передача, удаляем связанный с ней приход
        if (!empty($expense->transfer_to)) {
            ExpensesPersonal::where('transfer_id', $expense->id)->delete();
        }

        if ($data['page'] === 'manager_expenses') {
            return redirect()
                ->route('manager.expenses_all_personal.expenses_index', $data['manager_id'])
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Строка удалена'
                ]);
        } else {
            return redirect()
                ->route('manager.expenses_personal.index')
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Строка удалена'
                ]);
        }
    }
}
