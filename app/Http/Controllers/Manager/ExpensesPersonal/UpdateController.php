<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;

use App\Http\Requests\Manager\ExpensesPersonal\UpdateRequest;
use App\Models\ExpensesPersonal;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, ExpensesPersonal $expense)
    {
        $data = $request->validated();
        $page = $data['page'];
        unset($data['page']);
        $expense->update($data);
        $expense->refresh();

        // Удаляем связанный с передачей приход
        ExpensesPersonal::where('transfer_id', $expense->id)->delete();

        // Если это передача, создаём у другого сотрудника приход
        if (!empty($expense->transfer_to)) {
            ExpensesPersonal::create([
                'manager_id' => $expense->transfer_to,
                'date' => $expense->date,
                'category' => 0,
                'transfer_id' => $expense->id,
                'sum_string' => $expense->sum_string,
                'sum' => $expense->sum,
                'comment' => 'Передал ' . $expense->manager?->name . (!empty($expense->comment) ? ', ' . $expense->comment : '')
            ]);
        }

        if ($page === 'manager_expenses') {
            return redirect()
                ->route('manager.expenses_all_personal.expenses_index', $data['manager_id'])
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Данные обновлены'
                ]);
        } else {
            return redirect()
                ->route('manager.expenses_personal.index')
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'Данные обновлены'
                ]);
        }
    }
}
