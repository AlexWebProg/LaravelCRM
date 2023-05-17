<?php

namespace App\Services\Manager\ExpensesMatReport;

use App\Models\ExpensesObject;
use App\Models\ExpensesPersonal;
use Carbon\Carbon;

class Service
{
    // Возвращает отчёт по материалам за месяц
    public function getMonthReport(string $month_year): array
    {
        $dt = Carbon::parse('01.'.$month_year);
        $month_report = [
            'data' => [],
            'month_str' => $dt->isoFormat('MMMM'),
            'month_year_str' => $dt->isoFormat('MMMM YYYY'),
            'year' => $dt->format('Y'),
            'column_sum' => [
                'goods_sum' => 0,
                'goods_sum_str' => '',
                'tools_sum' => 0,
                'tools_sum_str' => '',
                'auto_sum' => 0,
                'auto_sum_str' => '',
                'salary_sum' => 0,
                'salary_sum_str' => '',
                'received_sum' => 0,
                'received_sum_str' => '',
            ],
            'total_spent' => 0,
            'total_spent_sum' => '',
            'total_left' => 0,
            'total_left_sum' => '',
        ];
        for ($day = 1; $day <= $dt->daysInMonth; $day++) {
            $strDate = $day . '.' . $month_year;
            $dbDate = date('Y-m-d',strtotime($strDate));

            // Краткое описание товара
            $arGoodsInfo = [];
            $intGoodsSum = 0;

            // Инструменты
            $arToolsInfo = [];
            $intToolsSum = 0;

            // Автомобиль
            $arAutoInfo = [];
            $intAutoSum = 0;

            // Ребятам
            $arSalaryInfo = [];
            $intSalarySum = 0;

            // Передачи
            $arTransferInfo = [];

            // Получено
            $arReceivedInfo = [];
            $intReceivedSum = 0;

            // Расходы по объектам и гарантии
            $arExpensesObject = ExpensesObject::where('date',$dbDate)->get();
            if (count($arExpensesObject)) {
                foreach ($arExpensesObject as $expense) {
                    if (!empty($expense->chk_amount)) {
                        $arGoodsInfo[] = $expense->object_name_or_guarantee . ': '
                            . $expense->goods_sum_description . ', '
                            . $expense->chk_amount_str . ' - '
                            . $expense->author_extended;
                        $intGoodsSum = $intGoodsSum + $expense->chk_amount;
                    }
                    if (!empty($expense->garb_amount)) {
                        $arGoodsInfo[] = $expense->object_name_or_guarantee . ': '
                            . 'расходы по мусору, '
                            . $expense->garb_amount_str . ' - '
                            . $expense->author_extended;
                        $intGoodsSum = $intGoodsSum + $expense->garb_amount;
                    }
                    if (!empty($expense->tool_amount)) {
                        $arToolsInfo[] = $expense->object_name_or_guarantee . ': '
                            . $expense->tools_description . ', '
                            . $expense->tool_amount_str . ' - '
                            . $expense->author_extended;
                        $intToolsSum = $intToolsSum + $expense->tool_amount;
                    }
                    if (!empty($expense->received_sum)) {
                        $arSalaryInfo[] = $expense->object_name_or_guarantee . ': '
                            . $expense->work_pay_description . ', '
                            . $expense->received_sum_str . ' - '
                            . $expense->author_extended;
                        $intSalarySum = $intSalarySum + $expense->received_sum;
                    }
                }
            }

            // Отчёт по расходам
            $arExpensesPersonal = ExpensesPersonal::where('date',$dbDate)->get();
            if (count($arExpensesPersonal)) {
                foreach ($arExpensesPersonal as $expense) {
                    switch ($expense->category) {
                        case 0: // Приход
                            if (is_null($expense->transfer_id)) {
                                // Если это не передача, суммируем в столбец "Получено"
                                $arReceivedInfo[] = $expense->description . ', '
                                    . $expense->sum_str . ' - '
                                    . $expense->author_extended;
                                $intReceivedSum = $intReceivedSum + $expense->sum;
                            }
                            break;
                        case 1: // Расход на автомобиль
                            $arAutoInfo[] = $expense->description . ', '
                                . $expense->sum_str . ' - '
                                . $expense->author_extended;
                            $intAutoSum = $intAutoSum + $expense->sum;
                            break;
                        case 2: // Другой расход
                            $arGoodsInfo[] = $expense->description . ', '
                                . $expense->sum_str . ' - '
                                . $expense->author_extended;
                            $intGoodsSum = $intGoodsSum + $expense->sum;
                            break;
                        case 3: // Зарплата техконтролю
                            $arSalaryInfo[] = $expense->description . ', '
                                . $expense->sum_str . ' - '
                                . $expense->author_extended;
                            $intSalarySum = $intSalarySum + $expense->sum;
                            break;
                        case 4: // Передача другому сотруднику
                            $arTransferInfo[] = 'Передал: ' . $expense->transfer_from_name . ', <br/>'
                                . 'Кому: ' . $expense->transfer_to_name . ', <br/>'
                                . $expense->description . ', <br/>'
                                . $expense->sum_str;
                            break;
                    }
                }
            }

            $month_report['data'][] = [
                'date' => Carbon::parse($strDate)->isoFormat('D MMM'),

                'goods_info' => count($arGoodsInfo) ? '<ul class="m-0 pl-3"><li>' . implode('</li><li>',array_unique($arGoodsInfo)).'</li></ul>' : '',
                'goods_sum' => $intGoodsSum,
                'goods_sum_str' => formatMoney($intGoodsSum),

                'tools_info' => count($arToolsInfo) ? '<ul class="m-0 pl-3"><li>' . implode('</li><li>',array_unique($arToolsInfo)).'</li></ul>' : '',
                'tools_sum' => $intToolsSum,
                'tools_sum_str' => formatMoney($intToolsSum),

                'auto_info' => count($arAutoInfo) ? '<ul class="m-0 pl-3"><li>' . implode('</li><li>',array_unique($arAutoInfo)).'</li></ul>' : '',
                'auto_sum' => $intAutoSum,
                'auto_sum_str' => formatMoney($intAutoSum),

                'salary_info' => count($arSalaryInfo) ? '<ul class="m-0 pl-3"><li>' . implode('</li><li>',array_unique($arSalaryInfo)).'</li></ul>' : '',
                'salary_sum' => $intSalarySum,
                'salary_sum_str' => formatMoney($intSalarySum),

                'transfer_info' => count($arTransferInfo) ? '<ul class="m-0 pl-3"><li>' . implode('</li><li>',array_unique($arTransferInfo)).'</li></ul>' : '',

                'received_info' => count($arReceivedInfo) ? '<ul class="m-0 pl-3"><li>' . implode('</li><li>',array_unique($arReceivedInfo)).'</li></ul><hr/><p class="text-right">Итого '.formatMoney($intReceivedSum).'</p>' : '',
                'received_sum' => $intReceivedSum,
            ];

        }
        if (count($month_report['data'])) {
            foreach ($month_report['data'] as $row) {
                $month_report['column_sum']['goods_sum'] = $month_report['column_sum']['goods_sum'] + $row['goods_sum'];
                $month_report['column_sum']['tools_sum'] = $month_report['column_sum']['tools_sum'] + $row['tools_sum'];
                $month_report['column_sum']['auto_sum'] = $month_report['column_sum']['auto_sum'] + $row['auto_sum'];
                $month_report['column_sum']['salary_sum'] = $month_report['column_sum']['salary_sum'] + $row['salary_sum'];
                $month_report['column_sum']['received_sum'] = $month_report['column_sum']['received_sum'] + $row['received_sum'];
            }
        }
        $month_report['column_sum']['goods_sum_str'] = formatMoney($month_report['column_sum']['goods_sum'],true);
        $month_report['column_sum']['tools_sum_str'] = formatMoney($month_report['column_sum']['tools_sum'],true);
        $month_report['column_sum']['auto_sum_str'] = formatMoney($month_report['column_sum']['auto_sum'],true);
        $month_report['column_sum']['salary_sum_str'] = formatMoney($month_report['column_sum']['salary_sum'],true);
        $month_report['column_sum']['received_sum_str'] = formatMoney($month_report['column_sum']['received_sum'],true);

        $month_report['total_spent'] = $month_report['column_sum']['goods_sum'] + $month_report['column_sum']['tools_sum'] +
            $month_report['column_sum']['auto_sum'] + $month_report['column_sum']['salary_sum'];
        $month_report['total_spent_str'] = formatMoney($month_report['total_spent'],true);

        $month_report['total_left'] = $month_report['column_sum']['received_sum'] - $month_report['total_spent'];
        $month_report['total_left_str'] = formatMoney($month_report['total_left'],true);

        return $month_report;
    }

    // Возвращает отчёт по материалам за год
    public function getYearReport(int $year): array
    {
        $arYearReport = [
            'months' => [],
            'goods_sum' => 0,
            'tools_sum' => 0,
            'auto_sum' => 0,
            'salary_sum' => 0,
            'received_sum' => 0,
            'total_received' => 0
        ];
        for ($month_num = 1; $month_num <= 12; $month_num++) {
            $dt = Carbon::parse('01.'.$month_num.'.'.$year);
            $month_report = $this->getMonthReport($dt->format('m.Y'));

            // Все суммы за январь и февраль 2023 фиктивно делаем нулями
            if (in_array($month_num,[1,2]) && $year === 2023) {
                $month_report['column_sum']['goods_sum'] = 0;
                $month_report['column_sum']['tools_sum'] = 0;
                $month_report['column_sum']['auto_sum'] = 0;
                $month_report['column_sum']['salary_sum'] = 0;
                $month_report['column_sum']['received_sum'] = 0;

                $month_report['column_sum']['goods_sum_str'] = formatMoney($month_report['column_sum']['goods_sum'],true);
                $month_report['column_sum']['tools_sum_str'] = formatMoney($month_report['column_sum']['tools_sum'],true);
                $month_report['column_sum']['auto_sum_str'] = formatMoney($month_report['column_sum']['auto_sum'],true);
                $month_report['column_sum']['salary_sum_str'] = formatMoney($month_report['column_sum']['salary_sum'],true);
                $month_report['column_sum']['received_sum_str'] = formatMoney($month_report['column_sum']['received_sum'],true);
            }

            $arYearReport['months'][$month_num] = $month_report;
            $arYearReport['goods_sum'] = $arYearReport['goods_sum'] + $month_report['column_sum']['goods_sum'];
            $arYearReport['tools_sum'] = $arYearReport['tools_sum'] + $month_report['column_sum']['tools_sum'];
            $arYearReport['auto_sum'] = $arYearReport['auto_sum'] + $month_report['column_sum']['auto_sum'];
            $arYearReport['salary_sum'] = $arYearReport['salary_sum'] + $month_report['column_sum']['salary_sum'];
            $arYearReport['received_sum'] = $arYearReport['received_sum'] + $month_report['column_sum']['received_sum'];
        }
        $arYearReport['goods_sum_str'] = formatMoney($arYearReport['goods_sum'],true);
        $arYearReport['tools_sum_str'] = formatMoney($arYearReport['tools_sum'],true);
        $arYearReport['auto_sum_str'] = formatMoney($arYearReport['auto_sum'],true);
        $arYearReport['salary_sum_str'] = formatMoney($arYearReport['salary_sum'],true);
        $arYearReport['received_sum_str'] = formatMoney($arYearReport['received_sum'],true);

        $arYearReport['total_spent'] = $arYearReport['goods_sum'] + $arYearReport['tools_sum'] +
            $arYearReport['auto_sum'] + $arYearReport['salary_sum'];
        $arYearReport['total_spent_str'] = formatMoney($arYearReport['total_spent'],true);

        $arYearReport['total_left'] = $arYearReport['received_sum'] - $arYearReport['total_spent'];
        $arYearReport['total_left_str'] = formatMoney($arYearReport['total_left'],true);

        return $arYearReport;
    }

}
