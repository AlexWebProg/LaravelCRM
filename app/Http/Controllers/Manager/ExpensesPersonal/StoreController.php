<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;

use App\Http\Requests\Manager\ExpensesPersonal\StoreRequest;
use App\Models\ExpensesPersonal;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $page = $data['page'];
        unset($data['page']);
        $expense = ExpensesPersonal::create($data);
        if (empty($expense)) {
            $arNotification = [
                'class' => 'danger',
                'message' => 'При добавлении строки произошла неизвестная ошибка.<br/>Строка не была добавлена'
            ];
        } else {
            $arNotification = [
                'class' => 'success',
                'message' => 'Строка добавлена'
            ];

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

        }
        if ($page === 'manager_expenses') {
            return redirect()
                ->route('manager.expenses_all_personal.expenses_index', $data['manager_id'])
                ->with('notification', $arNotification);
        } else {
            return redirect()
                ->route('manager.expenses_personal.index')
                ->with('notification', $arNotification);
        }
    }
}
