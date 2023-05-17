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

class ExpensesMatReportExcelExportSheetMonth implements FromArray,
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
                $workSheet->getStyle('C')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('E')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('G')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('I')->getAlignment()->setHorizontal('right');
                $workSheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
                $workSheet->getStyle('A:K')->getAlignment()->setVertical('center');

                $workSheet->mergeCells('B' . ($event->sheet->getHighestRow() - 2) . ':C'. ($event->sheet->getHighestRow() - 2));
                $workSheet->mergeCells('D' . ($event->sheet->getHighestRow() - 2) . ':E'. ($event->sheet->getHighestRow() - 2));
                $workSheet->mergeCells('F' . ($event->sheet->getHighestRow() - 2) . ':G'. ($event->sheet->getHighestRow() - 2));
                $workSheet->mergeCells('H' . ($event->sheet->getHighestRow() - 2) . ':I'. ($event->sheet->getHighestRow() - 2));

                $workSheet->getStyle('B' . ($event->sheet->getHighestRow() - 2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('D' . ($event->sheet->getHighestRow() - 2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('F' . ($event->sheet->getHighestRow() - 2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('H' . ($event->sheet->getHighestRow() - 2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('K' . ($event->sheet->getHighestRow() - 2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $workSheet->mergeCells('A' . ($event->sheet->getHighestRow() - 1) . ':E'. ($event->sheet->getHighestRow() - 1));
                $workSheet->mergeCells('F' . ($event->sheet->getHighestRow() - 1) . ':K'. ($event->sheet->getHighestRow() - 1));
                $workSheet->getStyle('F' . ($event->sheet->getHighestRow() - 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 1) . ':K'. ($event->sheet->getHighestRow() - 1))->getAlignment()->setHorizontal('center');

                $workSheet->mergeCells('A' . ($event->sheet->getHighestRow()) . ':E'. ($event->sheet->getHighestRow()));
                $workSheet->mergeCells('F' . ($event->sheet->getHighestRow()) . ':K'. ($event->sheet->getHighestRow()));
                $workSheet->getStyle('F' . ($event->sheet->getHighestRow()))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $workSheet->getStyle('A' . ($event->sheet->getHighestRow()) . ':K'. ($event->sheet->getHighestRow()))->getAlignment()->setHorizontal('center');

                $event->sheet->getStyle("A1:K".$event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ]
                ]);

                $workSheet->getStyle('A1:K1')->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 2) . ':K'. ($event->sheet->getHighestRow()))->applyFromArray([
                    'font' => ['bold' => true]
                ]);

                $workSheet->getStyle('B1:C' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'f2f2f2']
                    ],
                ]);

                $workSheet->getStyle('D1:E' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fdefdd']
                    ],
                ]);

                $workSheet->getStyle('F1:G' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'ebe5fb']
                    ],
                ]);

                $workSheet->getStyle('H1:I' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fedbda']
                    ],
                ]);

                $workSheet->getStyle('J1:J' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'def6fc']
                    ],
                ]);

                $workSheet->getStyle('K1:K' . ($event->sheet->getHighestRow() - 2))->applyFromArray([
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

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow() - 1) . ':K'. ($event->sheet->getHighestRow() - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'fcf3cf']
                    ],
                ]);

                $workSheet->getStyle('A' . ($event->sheet->getHighestRow()) . ':K'. ($event->sheet->getHighestRow()))->applyFromArray([
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
            'A' => 9,
            'B' => 50,
            'D' => 50,
            'F' => 50,
            'H' => 50,
            'J' => 50,
            'K' => 50,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

}
