<?php

namespace App\Services\Manager\ExpensesPersonal;

use App\Models\ExpensesObject;
use App\Models\ExpensesPersonal;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class Service
{
    // Возвращает все расходы и приходы сотрудника
    public function getAllManagerExpenses(int $manager_id) : array
    {
        $arExpenses = [];
        $expenses = ExpensesObject::where('manager_id',$manager_id)->get();
        $expenses = $expenses->concat(ExpensesPersonal::where('manager_id',$manager_id)->get());
        $expenses = $expenses->sortBy([
            ['date', 'asc'],
            ['created_at', 'asc'],
            ['id', 'asc'],
        ]);
        if (count($expenses)) {
            foreach ($expenses as $expense) {
                if ($expense instanceof ExpensesObject) {
                    // Расходы по объектам
                    if (!empty($expense->chk_amount)) {
                        $arExpenses[] = [
                            'id' => $expense->id,
                            'date_sort' => $expense->date,
                            'date' => $expense->date_str,
                            'created_at' => $expense->created_at,
                            'type' => 'object',
                            'client_id' => $expense->client_id,
                            'is_income' => 0,
                            'comment' => (empty($expense->client_id) ? 'Расходы по гарантии' : $expense->client->address) .
                                ', сумма по чекам, ' . $expense->goods_sum_description,
                            'sum' => $expense->chk_amount,
                            'sum_str' => formatMoney($expense->chk_amount,true),
                            'initial_object' => $expense
                        ];
                    }
                    if (!empty($expense->garb_amount)) {
                        $arExpenses[] = [
                            'id' => $expense->id,
                            'date_sort' => $expense->date,
                            'date' => $expense->date_str,
                            'created_at' => $expense->created_at,
                            'type' => 'object',
                            'client_id' => $expense->client_id,
                            'is_income' => 0,
                            'comment' => (empty($expense->client_id) ? 'Расходы по гарантии' : $expense->client->address) .
                                ', сумма по мусору',
                            'sum' => $expense->garb_amount,
                            'sum_str' => formatMoney($expense->garb_amount,true),
                            'initial_object' => $expense
                        ];
                    }
                    if (!empty($expense->tool_amount)) {
                        $arExpenses[] = [
                            'id' => $expense->id,
                            'date_sort' => $expense->date,
                            'date' => $expense->date_str,
                            'created_at' => $expense->created_at,
                            'type' => 'object',
                            'client_id' => $expense->client_id,
                            'is_income' => 0,
                            'comment' => (empty($expense->client_id) ? 'Расходы по гарантии' : $expense->client->address) .
                                ', сумма по инструментам, ' . $expense->tools_description,
                            'sum' => $expense->tool_amount,
                            'sum_str' => formatMoney($expense->tool_amount,true),
                            'initial_object' => $expense
                        ];
                    }
                    if (!empty($expense->received_sum)) {
                        $arExpenses[] = [
                            'id' => $expense->id,
                            'date_sort' => $expense->date,
                            'date' => $expense->date_str,
                            'created_at' => $expense->created_at,
                            'type' => 'object',
                            'client_id' => $expense->client_id,
                            'is_income' => 0,
                            'comment' => (empty($expense->client_id) ? 'Расходы по гарантии' : $expense->client->address) .
                                ', оплата работ, ' . $expense->work_pay_description,
                            'sum' => $expense->received_sum,
                            'sum_str' => formatMoney($expense->received_sum,true),
                            'initial_object' => $expense
                        ];
                    }
                } elseif ($expense instanceof ExpensesPersonal) {
                    // Расходы и приходы сотрудника
                    $arExpenses[] = [
                        'id' => $expense->id,
                        'date_sort' => $expense->date,
                        'date' => $expense->date_str,
                        'created_at' => $expense->created_at,
                        'type' => 'personal',
                        'is_income' => empty($expense->category) ? 1 : 0,
                        'sum' => $expense->sum,
                        'sum_str' => formatMoney($expense->sum,true),
                        'comment' => $expense->category_str . ': '
                            . ((!empty($expense->transfer_to) && !empty($expense->transfer_to_manager?->name)) ? $expense->transfer_to_manager->name . ', ' : '')
                            . $expense->description,
                        'initial_object' => $expense
                    ];
                }
            }
        }
        // Баланс на конец каждого дня
        $arDayBalance = [];
        $intDayBalance = 0;
        if (count($arExpenses)) {
            foreach ($arExpenses as $k => $arExpense) {
                if (!empty($arExpense['is_income'])) {
                    $intDayBalance = $intDayBalance + $arExpense['sum'];
                } else {
                    $intDayBalance = $intDayBalance - $arExpense['sum'];
                }
                if (empty($arExpenses[$k+1]) || $arExpenses[$k+1]['date'] != $arExpense['date']) {
                    $arDayBalance[$arExpense['date']] = $intDayBalance;
                }
            }
            foreach ($arExpenses as $k => $arExpense) {
                $arExpenses[$k]['day_balance'] = $arDayBalance[$arExpense['date']];
                $arExpenses[$k]['day_balance_str'] = formatMoney($arDayBalance[$arExpense['date']],true);
            }
        }

        // Упорядочиваем расходы по дате в порядке убывания
        array_multisort(
            array_column($arExpenses, 'date_sort'), SORT_DESC,
            array_column($arExpenses, 'created_at'), SORT_DESC,
            array_column($arExpenses, 'id'), SORT_DESC,
            $arExpenses);

        return $arExpenses;
    }

    // Возвращает баланс сотрудника
    public function getExpensesBalance(array $arExpenses) : string
    {
        $intBalance = 0;
        if (count($arExpenses)) {
            foreach ($arExpenses as $arExpense) {
                if (!empty($arExpense['is_income'])) {
                    $intBalance = $intBalance + $arExpense['sum'];
                } else {
                    $intBalance = $intBalance - $arExpense['sum'];
                }
            }
        }
        return formatMoney($intBalance, true);
    }

    // Возвращает значения для заполнения полей формы расхода
    public function getExpensePersonaFormData($manager = null) : array
    {
        $managers = User::where('is_active', 1)
            ->where('type', 1)
            ->where('id', '!=', ((!empty($manager?->id)) ? $manager->id : auth()->user()->id))
            ->orderBy('name')
            ->get();
        $expense_available_categories = ExpensesPersonal::getAvailableCategories();
        // Если не может создавать приходы, убираем их из списка
        if (!Gate::allows('create_expense_income',$manager)) {
            unset($expense_available_categories[0]);
        }
        return [
            'managers' => $managers,
            'expense_available_categories' => $expense_available_categories
        ];
    }

}
