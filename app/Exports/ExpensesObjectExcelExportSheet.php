<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExpensesObjectExcelExportSheet implements FromArray,
    WithHeadings, ShouldAutoSize, WithTitle, WithEvents, WithColumnWidths, WithColumnFormatting
{

    protected string $sheet_title;
    protected array $arHeadings;
    protected array $arEstimateSum;
    protected array $arRows;

    public function __construct($sheet_title, $arHeadings, $arEstimateSum, $arRows)
    {
        $this->sheet_title = $sheet_title;
        $this->arHeadings = $arHeadings;
        $this->arEstimateSum = $arEstimateSum;
        $this->arRows = $arRows;
    }

    public function array(): array
    {
        return $this->arRows;
    }

    public function headings(): array
    {
        return [$this->arHeadings, ['','','=SUM(C3:C1000)','=SUM(D3:D1000)','','=SUM(F3:F1000)',$this->arEstimateSum['G'],'=G2-D2-C2-F2','','=SUM(J3:J1000)',$this->arEstimateSum['K'],'=K2-J2','']];
    }

    public function title(): string
    {
        return $this->sheet_title;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->freezePaneByColumnAndRow(2,3); // freezing here
                $workSheet->getStyle('A:B')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('C:D')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('E')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('F:H')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('I')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('J:L')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('M')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('A1:M1')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('A:M')->getAlignment()->setVertical('center');
                $workSheet->mergeCells('A1:A2');
                $workSheet->mergeCells('B1:B2');
                $workSheet->mergeCells('E1:E2');
                $workSheet->mergeCells('I1:I2');
                $workSheet->mergeCells('M1:M2');

                $event->sheet->getStyle("A1:M".$event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ]
                ]);

                $workSheet->getStyle('A1:M2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'e0f9e0']
                    ],
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('C3:D'.$event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'f2f2f2']
                    ],
                ]);

                $workSheet->getStyle('F3:H'.$event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'f2f2f2']
                    ],
                ]);

                $workSheet->getStyle('J3:L'.$event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'f2f2f2']
                    ],
                ]);

                $event->sheet->getStyle("A1:A".$event->sheet->getHighestRow())->applyFromArray([
                    'font' => ['bold' => true]
                ]);

            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 50,
            'E' => 50,
            'I' => 50,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

}
