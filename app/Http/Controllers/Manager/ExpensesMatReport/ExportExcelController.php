<?php

namespace App\Http\Controllers\Manager\ExpensesMatReport;

use App\Exports\ExpensesMatReportExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends BaseController
{
    // Экспорт сводки по объектам в excel
    public function __invoke(int $year)
    {
        $year_report = $this->service->getYearReport($year);

        // Отчёт за месяц
        $arMonthData = [];
        foreach($year_report['months'] as $intMonthNum => $arMonth) {
            $arMonthData[$intMonthNum] = [
                'sheet_name' => $arMonth['month_year_str'],
                'headings' => [
                    'Дата',
                    'Краткое описание товара',
                    'Чеки / сумма',
                    'Кому / какой?',
                    'Инструменты / сумма',
                    'Кто?',
                    'Заправки / сумма',
                    'Кому?',
                    'Ребятам / сумма',
                    'Передача / пояснение / сумма',
                    'Получено'
                ],
                'data' => [],
                'summary' => [
                    [
                        'Итого:',
                        kopToRub($arMonth['column_sum']['goods_sum'], true),
                        '',
                        kopToRub($arMonth['column_sum']['tools_sum'], true),
                        '',
                        kopToRub($arMonth['column_sum']['auto_sum'], true),
                        '',
                        kopToRub($arMonth['column_sum']['salary_sum'], true),
                        '',
                        '',
                        kopToRub($arMonth['column_sum']['received_sum'], true)
                    ],
                    [
                        'Итого потрачено:',
                        '',
                        '',
                        '',
                        '',
                        kopToRub($arMonth['total_spent'], true),
                        '',
                        '',
                        '',
                        '',
                        ''
                    ],
                    [
                        'Итого остаток:',
                        '',
                        '',
                        '',
                        '',
                        kopToRub($arMonth['total_left'], true),
                        '',
                        '',
                        '',
                        '',
                        ''
                    ]
                ]
            ];
            foreach ($arMonth['data'] as $intDayNum => $arDay) {
                $arMonthData[$intMonthNum]['data'][$intDayNum] = [
                    $arDay['date'],
                    excelLineBreak($arDay['goods_info']),
                    kopToRub($arDay['goods_sum']),
                    excelLineBreak($arDay['tools_info']),
                    kopToRub($arDay['tools_sum']),
                    excelLineBreak($arDay['auto_info']),
                    kopToRub($arDay['auto_sum']),
                    excelLineBreak($arDay['salary_info']),
                    kopToRub($arDay['salary_sum']),
                    excelLineBreak($arDay['transfer_info']),
                    excelLineBreak($arDay['received_info']),
                ];
            }
        }

        // Отчёт за год
        $arYearData = [
            'sheet_name' => 'Общая статистика',
            'headings' => [
                'Месяц',
                'Чеки / сумма',
                'Инструменты / сумма',
                'Заправки / сумма',
                'Ребятам / сумма',
                'Получено'
            ],
            'data' => [],
            'summary' => [
                [
                    'Итого:',
                    kopToRub($year_report['goods_sum'], true),
                    kopToRub($year_report['tools_sum'], true),
                    kopToRub($year_report['auto_sum'], true),
                    kopToRub($year_report['salary_sum'], true),
                    kopToRub($year_report['received_sum'], true),
                ],
                [
                    'Итого потрачено:',
                    '',
                    '',
                    kopToRub($year_report['total_spent'], true),
                    '',
                    '',
                    ''
                ],
                [
                    'Итого остаток:',
                    '',
                    '',
                    kopToRub($year_report['total_left'], true),
                    '',
                    '',
                    ''
                ]
            ]
        ];
        foreach($year_report['months'] as $intMonthNum => $arMonth) {
            $arYearData['data'][$intMonthNum] = [
                $arMonth['month_str'],
                kopToRub($arMonth['column_sum']['goods_sum'], true),
                kopToRub($arMonth['column_sum']['tools_sum'], true),
                kopToRub($arMonth['column_sum']['auto_sum'], true),
                kopToRub($arMonth['column_sum']['salary_sum'], true),
                kopToRub($arMonth['column_sum']['received_sum'], true),
            ];
        }

        // Экспорт в excel и отдача файла в браузер
        $export = new ExpensesMatReportExcelExport($arMonthData,$arYearData);
        return Excel::download($export, 'Отчёт по материалам за '.$year.' г -  '.date('Y-m-d').'.xlsx');
    }
}
