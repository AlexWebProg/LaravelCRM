<?php

namespace App\Http\Controllers\Manager\ClientSummary;

use App\Exports\ClientSummaryExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends BaseController
{
    // Экспорт сводки по объектам в excel
    public function __invoke()
    {
        $clients = $this->service->getClientSummaryData();
        // Заголовки столбцов
        $arHeadings = [
            'Объект',
            'Адрес',
            'Сроки выполнения работ (по договору)',
            'Мастер',
            'Камера',
            'План работ',
            'Проверка выполненных работ (этапы)',
            'Оплата',
            'Доставки материала',
            'Создан'
        ];
        // Данные таблицы
        $arData = [];
        foreach ($clients as $client) {
            $arData[] = [
                $client->address,
                $client->summary_address,
                $client->plan_dates,
                $client->summary_master,
                $client->summary_webcam,
                $client->plan_questions,
                $client->plan_check,
                $client->summary_pay,
                $client->summary_delivery,
                $client->created_str
            ];
        }
        // Экспорт в excel и отдача файла в браузер
        $export = new ClientSummaryExcelExport($arHeadings,$arData);
        return Excel::download($export, 'Объекты - сводка -  '.date('Y-m-d').'.xlsx');
    }
}
