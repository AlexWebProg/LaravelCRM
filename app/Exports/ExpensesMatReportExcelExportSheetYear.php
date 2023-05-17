<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExpensesMatReportExcelExportSheetYear implements FromArray,
    WithHeadings, WithTitle, WithEvents, WithColumnWidths, WithColumnFormatting
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
                $workSheet->getStyle('B:F')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('A:F')->getAlignment()->setVertical('center');

                $workSheet->mergeCells('A' . ($event->sheet->getHighestRow() - 1) . ':C'. ($event->sheet->getHighestRow() - 1));
                $workSheet->mergeCells('D' . ($event->sheet->getHighestRow() - 1) . ':F'. ($event->sheet->getHighestRow() - 1));
                $workSheet->getStyle('D' . ($event->sheet->getHighestRow() - 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 1) . ':F'. ($event->sheet->getHighestRow() - 1))->getAlignment()->setHorizontal('center');

                $workSheet->mergeCells('A' . ($event->sheet->getHighestRow()) . ':C'. ($event->sheet->getHighestRow()));
                $workSheet->mergeCells('D' . ($event->sheet->getHighestRow()) . ':F'. ($event->sheet->getHighestRow()));
                $workSheet->getStyle('D' . ($event->sheet->getHighestRow()))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('A' . ($event->sheet->getHighestRow()) . ':F'. ($event->sheet->getHighestRow()))->getAlignment()->setHorizontal('center');

                $event->sheet->getStyle("A1:F".$event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ]
                ]);

                $workSheet->getStyle('A1:F1')->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 2) . ':F'. ($event->sheet->getHighestRow()))->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('B1:B' . ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'f2f2f2']
                    ],
                ]);

                $workSheet->getStyle('C1:C' . ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fdefdd']
                    ],
                ]);

                $workSheet->getStyle('D1:D' . ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'ebe5fb']
                    ],
                ]);

                $workSheet->getStyle('E1:E' . ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fedbda']
                    ],
                ]);

                $workSheet->getStyle('F1:F' . ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'e0f9e0']
                    ],
                ]);

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'e8f1fe']
                    ],
                ]);

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 1) . ':F'. ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fcf3cf']
                    ],
                ]);

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow()) . ':F'. ($event->sheet->getHighestRow()))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'e0f9e0']
                    ],
                ]);

            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

}
