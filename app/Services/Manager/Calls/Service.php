<?php

namespace App\Services\Manager\Calls;

use App\Models\Calls;
use Carbon\Carbon;

class Service
{
    // Возвращает аналитику звонков за месяц
    public function getMonth(string $month_year): array
    {
        $dt = Carbon::parse('01.'.$month_year);
        $month = [
            'data' => [],
            'month_str' => $dt->isoFormat('MMMM'),
            'month_year_str' => $dt->isoFormat('MMMM YYYY'),
            'year' => $dt->format('Y'),
            'column_sum' => [
                'repair_full' => 0,
                'repair_partial' => 0,
                'advertising' => 0,
                'evening_calls' => 0,
                'day_total' => 0,
                'signed_up' => 0,
                'est_wo_dep' => 0,
                'from_youtube' => 0,
                'from_dzen' => 0,
                'from_rutube' => 0,
                'from_telegram' => 0,
                'from_tiktok' => 0,
                'from_vk' => 0,
                'from_site' => 0,
                'from_people' => 0,
                'from_other' => 0,
            ]
        ];
        // Выбираем аналитику за месяц
        $calls = Calls::whereBetween('date', [
            date('Y-m-d',strtotime('1.' . $month_year)),
            date('Y-m-d',strtotime($dt->daysInMonth . '.' . $month_year))
        ])->get();
        $arCalls = [];
        if (!empty($calls) && count($calls)) {
            foreach ($calls as $call) {
                $arCalls[$call->date] = $call;
            }
        }
        for ($day = 1; $day <= $dt->daysInMonth; $day++) {
            $strDate = $day . '.' . $month_year;
            $dbDate = date('Y-m-d',strtotime($strDate));
            $month['data'][$dbDate] = [
                'date' => Carbon::parse($strDate)->isoFormat('D MMM'),
                'db_date' => $dbDate,
                'repair_full' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->repair_full) ? 0 : $arCalls[$dbDate]->repair_full),
                'repair_partial' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->repair_partial) ? 0 : $arCalls[$dbDate]->repair_partial),
                'advertising' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->advertising) ? 0 : $arCalls[$dbDate]->advertising),
                'evening_calls' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->evening_calls) ? 0 : $arCalls[$dbDate]->evening_calls),
                'day_total' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->day_total) ? 0 : $arCalls[$dbDate]->day_total),
                'signed_up' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->repair_full) ? 0 : $arCalls[$dbDate]->signed_up),
                'est_wo_dep' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->est_wo_dep) ? 0 : $arCalls[$dbDate]->est_wo_dep),
                'from_youtube' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_youtube) ? 0 : $arCalls[$dbDate]->from_youtube),
                'from_dzen' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_dzen) ? 0 : $arCalls[$dbDate]->from_dzen),
                'from_rutube' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_rutube) ? 0 : $arCalls[$dbDate]->from_rutube),
                'from_telegram' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_telegram) ? 0 : $arCalls[$dbDate]->from_telegram),
                'from_tiktok' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_tiktok) ? 0 : $arCalls[$dbDate]->from_tiktok),
                'from_vk' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_vk) ? 0 : $arCalls[$dbDate]->from_vk),
                'from_site' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_site) ? 0 : $arCalls[$dbDate]->from_site),
                'from_people' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_people) ? 0 : $arCalls[$dbDate]->from_people),
                'from_other' => empty($arCalls[$dbDate]) ? '' : (empty($arCalls[$dbDate]->from_other) ? 0 : $arCalls[$dbDate]->from_other),
                'from' => '',
                'author' => empty($arCalls[$dbDate]) ? '' : $arCalls[$dbDate]->author_extended,
            ];
            if (!empty($arCalls[$dbDate])) {
                $arFrom = [];
                if (!empty($arCalls[$dbDate]->from_youtube)) $arFrom[] = 'YouTube: ' . $arCalls[$dbDate]->from_youtube;
                if (!empty($arCalls[$dbDate]->from_dzen)) $arFrom[] = 'Дзен: ' . $arCalls[$dbDate]->from_dzen;
                if (!empty($arCalls[$dbDate]->from_rutube)) $arFrom[] = 'RuTube: ' . $arCalls[$dbDate]->from_rutube;
                if (!empty($arCalls[$dbDate]->from_telegram)) $arFrom[] = 'Telegram: ' . $arCalls[$dbDate]->from_telegram;
                if (!empty($arCalls[$dbDate]->from_tiktok)) $arFrom[] = 'TikTok: ' . $arCalls[$dbDate]->from_tiktok;
                if (!empty($arCalls[$dbDate]->from_vk)) $arFrom[] = 'VK: ' . $arCalls[$dbDate]->from_vk;
                if (!empty($arCalls[$dbDate]->from_site)) $arFrom[] = 'Сайт: ' . $arCalls[$dbDate]->from_site;
                if (!empty($arCalls[$dbDate]->from_people)) $arFrom[] = 'От знакомых: ' . $arCalls[$dbDate]->from_people;
                if (!empty($arCalls[$dbDate]->from_other)) $arFrom[] = 'Другой: ' . $arCalls[$dbDate]->from_other;
                if (count($arFrom)) {
                    $month['data'][$dbDate]['from'] =  implode('<br/>',$arFrom);
                } else {
                    $month['data'][$dbDate]['from'] = 'нет';
                }
            }

        }
        if (count($month['data'])) {
            foreach ($month['data'] as $row) {
                if (!empty($row['repair_full'])) $month['column_sum']['repair_full'] = $month['column_sum']['repair_full'] + $row['repair_full'];
                if (!empty($row['repair_partial'])) $month['column_sum']['repair_partial'] = $month['column_sum']['repair_partial'] + $row['repair_partial'];
                if (!empty($row['advertising'])) $month['column_sum']['advertising'] = $month['column_sum']['advertising'] + $row['advertising'];
                if (!empty($row['evening_calls'])) $month['column_sum']['evening_calls'] = $month['column_sum']['evening_calls'] + $row['evening_calls'];
                if (!empty($row['day_total'])) $month['column_sum']['day_total'] = $month['column_sum']['day_total'] + $row['day_total'];
                if (!empty($row['signed_up'])) $month['column_sum']['signed_up'] = $month['column_sum']['signed_up'] + $row['signed_up'];
                if (!empty($row['est_wo_dep'])) $month['column_sum']['est_wo_dep'] = $month['column_sum']['est_wo_dep'] + $row['est_wo_dep'];
                if (!empty($row['from_youtube'])) $month['column_sum']['from_youtube'] = $month['column_sum']['from_youtube'] + $row['from_youtube'];
                if (!empty($row['from_dzen'])) $month['column_sum']['from_dzen'] = $month['column_sum']['from_dzen'] + $row['from_dzen'];
                if (!empty($row['from_rutube'])) $month['column_sum']['from_rutube'] = $month['column_sum']['from_rutube'] + $row['from_rutube'];
                if (!empty($row['from_telegram'])) $month['column_sum']['from_telegram'] = $month['column_sum']['from_telegram'] + $row['from_telegram'];
                if (!empty($row['from_tiktok'])) $month['column_sum']['from_tiktok'] = $month['column_sum']['from_tiktok'] + $row['from_tiktok'];
                if (!empty($row['from_vk'])) $month['column_sum']['from_vk'] = $month['column_sum']['from_vk'] + $row['from_vk'];
                if (!empty($row['from_site'])) $month['column_sum']['from_site'] = $month['column_sum']['from_site'] + $row['from_site'];
                if (!empty($row['from_people'])) $month['column_sum']['from_people'] = $month['column_sum']['from_people'] + $row['from_people'];
                if (!empty($row['from_other'])) $month['column_sum']['from_other'] = $month['column_sum']['from_other'] + $row['from_other'];
            }
        }
        $month['column_sum']['from'] = implode('<br/>',[
            'YouTube: ' . $month['column_sum']['from_youtube'],
            'Дзен: ' . $month['column_sum']['from_dzen'],
            'RuTube: ' . $month['column_sum']['from_rutube'],
            'Telegram: ' . $month['column_sum']['from_telegram'],
            'TikTok: ' . $month['column_sum']['from_tiktok'],
            'VK: ' . $month['column_sum']['from_vk'],
            'Сайт: ' . $month['column_sum']['from_site'],
            'От знакомых: ' . $month['column_sum']['from_people'],
            'Другой: ' . $month['column_sum']['from_other'],
        ]);
        return $month;
    }

    // Возвращает отчёт по материалам за год
    public function getYear(int $year): array
    {
        $arYearReport = [
            'months' => [],
            'column_sum' => [
                'repair_full' => 0,
                'repair_partial' => 0,
                'advertising' => 0,
                'evening_calls' => 0,
                'day_total' => 0,
                'signed_up' => 0,
                'est_wo_dep' => 0,
                'from_youtube' => 0,
                'from_dzen' => 0,
                'from_rutube' => 0,
                'from_telegram' => 0,
                'from_tiktok' => 0,
                'from_vk' => 0,
                'from_site' => 0,
                'from_people' => 0,
                'from_other' => 0,
            ]
        ];
        for ($month_num = 1; $month_num <= 12; $month_num++) {
            $dt = Carbon::parse('01.'.$month_num.'.'.$year);
            $month = $this->getMonth($dt->format('m.Y'));
            $arYearReport['months'][$month_num] = $month;
            $arYearReport['column_sum']['repair_full'] = $arYearReport['column_sum']['repair_full'] + $month['column_sum']['repair_full'];
            $arYearReport['column_sum']['repair_partial'] = $arYearReport['column_sum']['repair_partial'] + $month['column_sum']['repair_partial'];
            $arYearReport['column_sum']['advertising'] = $arYearReport['column_sum']['advertising'] + $month['column_sum']['advertising'];
            $arYearReport['column_sum']['evening_calls'] = $arYearReport['column_sum']['evening_calls'] + $month['column_sum']['evening_calls'];
            $arYearReport['column_sum']['day_total'] = $arYearReport['column_sum']['day_total'] + $month['column_sum']['day_total'];
            $arYearReport['column_sum']['signed_up'] = $arYearReport['column_sum']['signed_up'] + $month['column_sum']['signed_up'];
            $arYearReport['column_sum']['est_wo_dep'] = $arYearReport['column_sum']['est_wo_dep'] + $month['column_sum']['est_wo_dep'];
            $arYearReport['column_sum']['from_youtube'] = $arYearReport['column_sum']['from_youtube'] + $month['column_sum']['from_youtube'];
            $arYearReport['column_sum']['from_dzen'] = $arYearReport['column_sum']['from_dzen'] + $month['column_sum']['from_dzen'];
            $arYearReport['column_sum']['from_rutube'] = $arYearReport['column_sum']['from_rutube'] + $month['column_sum']['from_rutube'];
            $arYearReport['column_sum']['from_telegram'] = $arYearReport['column_sum']['from_telegram'] + $month['column_sum']['from_telegram'];
            $arYearReport['column_sum']['from_tiktok'] = $arYearReport['column_sum']['from_tiktok'] + $month['column_sum']['from_tiktok'];
            $arYearReport['column_sum']['from_vk'] = $arYearReport['column_sum']['from_vk'] + $month['column_sum']['from_vk'];
            $arYearReport['column_sum']['from_site'] = $arYearReport['column_sum']['from_site'] + $month['column_sum']['from_site'];
            $arYearReport['column_sum']['from_people'] = $arYearReport['column_sum']['from_people'] + $month['column_sum']['from_people'];
            $arYearReport['column_sum']['from_other'] = $arYearReport['column_sum']['from_other'] + $month['column_sum']['from_other'];
        }
        $arYearReport['column_sum']['from'] = implode('<br/>',[
            'YouTube: ' . $arYearReport['column_sum']['from_youtube'],
            'Дзен: ' . $arYearReport['column_sum']['from_dzen'],
            'RuTube: ' . $arYearReport['column_sum']['from_rutube'],
            'Telegram: ' . $arYearReport['column_sum']['from_telegram'],
            'TikTok: ' . $arYearReport['column_sum']['from_tiktok'],
            'VK: ' . $arYearReport['column_sum']['from_vk'],
            'Сайт: ' . $arYearReport['column_sum']['from_site'],
            'От знакомых: ' . $arYearReport['column_sum']['from_people'],
            'Другой: ' . $arYearReport['column_sum']['from_other'],
        ]);
        return $arYearReport;
    }

}
