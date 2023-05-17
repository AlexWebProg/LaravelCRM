<?php

namespace App\Services\Manager\ExpensesObject;

use App\Models\ExpensesObject;
use App\Models\User;

class ExpensesObjectService
{
    // Возвращает все расходы и приходы сотрудника
    public function getObjectExpenses(User $client = null) : array
    {
        $client_id = !empty($client?->id) ? $client->id : 0;
        $expenses = ExpensesObject::where('client_id',$client_id)->get();
        // Считаем шапку таблицы
        $expensesTotal = [
            'chk_amount' => 0,
            'garb_amount' => 0,
            'tool_amount' => 0,
            'estimate_sum_1' => !empty($client?->expense_estimate_sum_1) ? $client->expense_estimate_sum_1 : 0,
            'received_sum' => 0,
            'estimate_sum_2' => !empty($client?->expense_estimate_sum_2) ? $client->expense_estimate_sum_2 : 0,
        ];
        if (count($expenses)) {
            foreach ($expenses as $expense) {
                $expensesTotal['chk_amount'] += $expense->chk_amount;
                $expensesTotal['garb_amount'] += $expense->garb_amount;
                $expensesTotal['tool_amount'] += $expense->tool_amount;
                $expensesTotal['received_sum'] += $expense->received_sum;
            }
        }
        $expensesTotal['remainder_sum_1'] = $expensesTotal['estimate_sum_1'] - $expensesTotal['chk_amount'] - $expensesTotal['garb_amount'] - $expensesTotal['tool_amount'];
        $expensesTotal['remainder_sum_2'] = $expensesTotal['estimate_sum_2'] - $expensesTotal['received_sum'];
        // Предупреждения
        $warnings = [
            'materials_warning' => ($expensesTotal['remainder_sum_1'] < 5000000) ? 1 : 0,
            'materials_danger' => ($expensesTotal['remainder_sum_1'] <= 0) ? 1 : 0,
            'work_warning' => ($expensesTotal['remainder_sum_2'] < 5000000) ? 1 : 0,
            'work_danger' => ($expensesTotal['remainder_sum_2'] <= 0) ? 1 : 0
        ];
        foreach ($expensesTotal as &$value) {
            $value = formatMoney($value,true);
        }
        $expensesTotal['estimate_sum_1_form'] = !empty($client?->expense_estimate_sum_1) ? $client->expense_estimate_sum_1 : 0;
        $expensesTotal['estimate_sum_2_form'] = !empty($client?->expense_estimate_sum_2) ? $client->expense_estimate_sum_2 : 0;

        $expensesTotal['estimate_sum_1_string'] = !empty($client?->expense_estimate_sum_1_string) ? $client->expense_estimate_sum_1_string : '';
        $expensesTotal['estimate_sum_2_string'] = !empty($client?->expense_estimate_sum_2_string) ? $client->expense_estimate_sum_2_string : '';

        return [
            'expenses' => $expenses,
            'expensesTotal' => $expensesTotal,
            'expenses_warnings' => $warnings
        ];
    }


}
