<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\Style;

class ExpensesMatReportExcelExport implements WithMultipleSheets, WithDefaultStyles
{

    protected array $arMonthData;
    protected array $arYearData;

    public function __construct($arMonthData, $arYearData)
    {
        $this->arMonthData = $arMonthData;
        $this->arYearData = $arYearData;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->arMonthData as $arMonthData) {
            $sheets[] = new ExpensesMatReportExcelExportSheetMonth($arMonthData['sheet_name'],$arMonthData['headings'],$arMonthData['data'],$arMonthData['summary']);
        }
        $sheets[] = new ExpensesMatReportExcelExportSheetYear($this->arYearData['sheet_name'],$this->arYearData['headings'],$this->arYearData['data'],$this->arYearData['summary']);
        return $sheets;
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => [
                'size'   => 8,
                'name' => 'Arial'
            ],
            'alignment' => [
                'wrapText' => true,
            ],
        ];
    }

}
