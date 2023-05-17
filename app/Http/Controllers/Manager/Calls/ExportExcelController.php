<?php

namespace App\Http\Controllers\Manager\Calls;

use App\Exports\CallsExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends BaseController
{
    // Экспорт аналитики звонков в excel
    public function __invoke(int $year)
    {
        $year_report = $this->service->getYear($year);

        // Отчёт за месяц
        $arMonthData = [];
        foreach($year_report['months'] as $intMonthNum => $arMonth) {
            $arMonthData[$intMonthNum] = [
                'sheet_name' => $arMonth['month_year_str'],
                'headings' => [
                    'Дата',
                    'Ремонт целиком',
                    'Частичный ремонт',
                    'Реклама',
                    'Звонки после 19:00',
                    'Всего за день',
                    'Записались',
                    'Сметы без выезда',
                    'Откуда были звонки',
                    'Автор'
                ],
                'data' => [],
                'summary' => [
                    [
                        'Итого:',
                        $arMonth['column_sum']['repair_full'],
                        $arMonth['column_sum']['repair_partial'],
                        $arMonth['column_sum']['advertising'],
                        $arMonth['column_sum']['evening_calls'],
                        $arMonth['column_sum']['day_total'],
                        $arMonth['column_sum']['signed_up'],
                        $arMonth['column_sum']['est_wo_dep'],
                        str_replace('<br/>','
',$arMonth['column_sum']['from'])
                    ]
                ]
            ];
            foreach ($arMonth['data'] as $intDayNum => $arDay) {
                $arMonthData[$intMonthNum]['data'][$intDayNum] = [
                    $arDay['date'],
                    $arDay['repair_full'],
                    $arDay['repair_partial'],
                    $arDay['advertising'],
                    $arDay['evening_calls'],
                    $arDay['day_total'],
                    $arDay['signed_up'],
                    $arDay['est_wo_dep'],
                    str_replace('<br/>','
',$arDay['from']),
                    $arDay['author']
                ];
            }
        }

        // Отчёт за год
        $arYearData = [
            'sheet_name' => 'Всего за '.$year,
            'headings' => [
                'Месяц',
                'Ремонт целиком',
                'Частичный ремонт',
                'Реклама',
                'Звонки после 19:00',
                'Всего за месяц',
                'Записались',
                'Сметы без выезда',
                'Откуда были звонки'
            ],
            'data' => [],
            'summary' => [
                [
                    'Итого:',
                    $year_report['column_sum']['repair_full'],
                    $year_report['column_sum']['repair_partial'],
                    $year_report['column_sum']['advertising'],
                    $year_report['column_sum']['evening_calls'],
                    $year_report['column_sum']['day_total'],
                    $year_report['column_sum']['signed_up'],
                    $year_report['column_sum']['est_wo_dep'],
                    str_replace('<br/>','
',$year_report['column_sum']['from'])
                ]
            ]
        ];
        foreach($year_report['months'] as $intMonthNum => $arMonth) {
            $arYearData['data'][$intMonthNum] = [
                $arMonth['month_str'],
                $arMonth['column_sum']['repair_full'],
                $arMonth['column_sum']['repair_partial'],
                $arMonth['column_sum']['advertising'],
                $arMonth['column_sum']['evening_calls'],
                $arMonth['column_sum']['day_total'],
                $arMonth['column_sum']['signed_up'],
                $arMonth['column_sum']['est_wo_dep'],
                str_replace('<br/>','
',$arMonth['column_sum']['from'])
            ];
        }

        // Экспорт в excel и отдача файла в браузер
        $export = new CallsExcelExport($arMonthData,$arYearData);
        return Excel::download($export, 'Аналитика звонков за '.$year.' г -  '.date('Y-m-d').'.xlsx');
    }
}
