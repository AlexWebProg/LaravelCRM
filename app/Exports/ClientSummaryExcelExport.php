<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use \Maatwebsite\Excel\Writer;

use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ClientSummaryExcelExport implements FromArray,
    WithHeadings, WithDefaultStyles, WithEvents, WithColumnWidths
{

    protected $arHeadings;
    protected $arData;

    public function __construct($arHeadings, $arData)
    {
        $this->arHeadings = $arHeadings;
        $this->arData = $arData;
    }

    public function array(): array
    {
        return $this->arData;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 40,
            'C' => 40,
            'D' => 25,
            'E' => 25,
            'F' => 40,
            'G' => 40,
            'H' => 25,
            'I' => 25,
            'J' => 15
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents():array
    {
        Writer::macro('setDefaultStyle', function (Writer $writer) {
            $writer->getActiveSheet()->freezePane('A2');
            $writer->getActiveSheet()->setAutoFilter('A1:J1');
            $writer->getActiveSheet()->setTitle('Объекты - сводка');
            $writer->getActiveSheet()->getStyle('A:J')->getAlignment()->setHorizontal('center');
            $writer->getActiveSheet()->getStyle('A:J')->getAlignment()->setVertical('center')->setVertical('center');
        });

        return [
            BeforeWriting::class=>function(BeforeWriting $event){
                $event->writer->setDefaultStyle();
            },
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Or return the styles array
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

    public function headings(): array
    {
        return $this->arHeadings;
    }

}
