<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Exports\ExpensesObjectExcelExport;
use App\Models\User;
use App\Models\ExpensesObject;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExportExcelController extends Controller
{
    // Экспорт сводки по объектам в excel
    public function __invoke()
    {
        // Заголовки столбцов
        $arHeadings = [
            'Дата',
            'Чеки и доставка/подробно',
            'Суммы по чекам',
            'Суммы по мусору',
            'Пояснение по инструменту',
            'Суммы по инструменту',
            'Заложено по смете Сумма',
            'Остаток Сумма',
            'Оплата работ, кому и за что подробно',
            'Суммы полученные',
            'Заложено по смете Сумма',
            'Остаток Сумма',
            'Автор'
        ];

        // Данные таблицы
        $arData = [
            0 => [
                'sheet_name' => 'Выезды на косяки и гарантию',
                'estimate_sum' => ['G' => 0, 'K' => 0],
                'rows' => []
            ]
        ];
        $clients = User::where('is_active', 1)
            ->where('type', 0)
            ->orderBy('created_at', 'DESC')
            ->get();
        foreach ($clients as $client) {
            $arData[$client->id] = [
                'sheet_name' => $client->address,
                'estimate_sum' => ['G' => kopToRub($client->expense_estimate_sum_1), 'K' => kopToRub($client->expense_estimate_sum_2)],
                'rows' => []
            ];
        }
        $arExpensesObjects = ExpensesObject::all();
        foreach ($arExpensesObjects as $expense) {
            $arData[$expense->client_id]['rows'][] = [
                $expense->date_str,
                $expense->сhk_and_del_det,
                kopToRub($expense->chk_amount),
                kopToRub($expense->garb_amount),
                $expense->tool_comment,
                kopToRub($expense->tool_amount),
                '',
                '',
                $expense->work_pay,
                kopToRub($expense->received_sum),
                '',
                '',
                $expense->author,
            ];
        }

        // Экспорт в excel и отдача файла в браузер
        $export = new ExpensesObjectExcelExport($arHeadings,$arData);
        return Excel::download($export, 'Расходы по объектам -  '.date('Y-m-d').'.xlsx');
    }
}
