<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\Style;

class ExpensesObjectExcelExport implements WithMultipleSheets, WithDefaultStyles
{

    protected array $arHeadings;
    protected array $arData;

    public function __construct($arHeadings, $arData)
    {
        $this->arHeadings = $arHeadings;
        $this->arData = $arData;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->arData as $arData) {
            $sheets[] = new ExpensesObjectExcelExportSheet($arData['sheet_name'],$this->arHeadings,$arData['estimate_sum'],$arData['rows']);
        }
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
