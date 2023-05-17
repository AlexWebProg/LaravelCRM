<?php

namespace App\Services\Manager\ClientSummary;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class Service
{
    // Возвращает отсортированную таблицу сводки по объектам
    public function getClientSummaryData(){
        $clients = User::where('is_active', 1)
            ->where('type', 0)
            ->when(!Gate::allows('show_ob_status_2'), function ($query) {
                return $query->where('ob_status', '!=', 2);
            })
            ->get();
        $intNow = time();

        // Сортировка по срокам выполнения работ
        foreach ($clients as &$client) {
            $arDate = date_parse($client->plan_dates);
            if (in_array($client->id,config('global.arTestAndDemoObjects'))) { // Демо-объекты в самом низу
                $client->plan_dates_order = '9999-' . $client->id . '-01';
            }elseif ($client->ob_status === 2) { // Объекты на гарантии внизу, над демо-объектами
                $client->plan_dates_order = '9998-' . $arDate['year'].'-'.sprintf("%02d", $arDate['month']).'-'.sprintf("%02d", $arDate['day']);
            }elseif (!empty($arDate['year'])) {
                $client->plan_dates_order = $arDate['year'].'-'.sprintf("%02d", $arDate['month']).'-'.sprintf("%02d", $arDate['day']);
                // Получаем срок окончания работ и указываем, если осталось менее 2 месяцев, или одного
                $strHelper = substr_replace($client->plan_dates, '', 0, 12);
                $arEndDate = date_parse($strHelper);
                if (!empty($arEndDate['year'])) {
                    $intDaysDiff = (strtotime($arEndDate['year'].'-'.$arEndDate['month'].'-'.$arEndDate['day']) - $intNow) / (60*60*24);
                    if ($intDaysDiff < 30) {
                        $client->one_month_before_deadline = 1;
                    } elseif ($intDaysDiff < 61) {
                        $client->two_month_before_deadline = 1;
                    }
                    unset($intDaysDiff);
                }
                unset($strHelper,$arEndDate);
            } else {
                $client->plan_dates_order = '';
            }
            unset($arDate);
        }
        $clients = $clients->sortBy('plan_dates_order');

        // Добавляем заголовки к разделам
        foreach ($clients as &$client) {
            if (in_array($client->id,config('global.arTestAndDemoObjects')) && empty($demo_header)) {
                $client->demo_header = 1;
                $demo_header = 1;
            } elseif ($client->ob_status === 2 && empty($ob_status_2_header)) {
                $client->ob_status_2_header = 1;
                $ob_status_2_header = 1;
            }
        }

        return $clients;
    }
}
