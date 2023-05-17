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

class CallsExcelExportSheetMonth implements FromArray,
    WithHeadings, ShouldAutoSize, WithTitle, WithEvents, WithColumnWidths, WithColumnFormatting
{

    protected string $strSheetTitle;
    protected array $arHeadings;
    protected array $arRows;
    protected array $arSummary;

    public function __construct($strSheetTitle, $arHeadings, $arRows, $arSummary)
    {
        $this->strSheetTitle = $strSheetTitle;
        $this->arHeadings = $arHeadings;
        $this->arRows = $arRows;
        $this->arSummary = $arSummary;
    }

    public function array(): array
    {
        return array_merge($this->arRows,$this->arSummary);
    }

    public function headings(): array
    {
        return [$this->arHeadings];
    }

    public function title(): string
    {
        return $this->strSheetTitle;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->freezePaneByColumnAndRow(2,2); // freezing here
                $workSheet->getStyle('A')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('B:I')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('J')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('A:J')->getAlignment()->setVertical('center');


                $event->sheet->getStyle("A1:J".$event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ]
                ]);

                $workSheet->getStyle('A1:J1')->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('A' . $event->sheet->getHighestRow() . ':J'. ($event->sheet->getHighestRow()))->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('B1:B' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'f2f2f2']
                    ],
                ]);

                $workSheet->getStyle('C1:C' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fdefdd']
                    ],
                ]);

                $workSheet->getStyle('D1:D' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'ebe5fb']
                    ],
                ]);

                $workSheet->getStyle('E1:E' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fedbda']
                    ],
                ]);

                $workSheet->getStyle('F1:F' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'def6fc']
                    ],
                ]);

                $workSheet->getStyle('G1:G' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'e0f9e0']
                    ],
                ]);

                $workSheet->getStyle('H1:H' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'eafefe']
                    ],
                ]);

                $workSheet->getStyle('I1:I' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fdf5eb']
                    ],
                ]);

                $workSheet->getStyle('J1:J' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'ebfdf4']
                    ],
                ]);

                $workSheet->getStyle('A' . $event->sheet->getHighestRow())->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'e8f1fe']
                    ],
                ]);

            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 9,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 30,
            'J' => 30,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
        ];
    }

}
