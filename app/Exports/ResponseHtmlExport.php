<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ResponseHtmlExport extends DefaultValueBinder implements WithDrawings, FromView, WithEvents,  WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $res;

    public function drawings()
    {
        $drawing = new Drawing();
        return $drawing;
    }

    public function __construct($res)
    {
        $this->res = $res;
    }

    public function view(): View
    {

        return view('responses.excel', [
            'model' => $this->res
        ]);
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }


    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $styleArray = [
                    'numberformat' => [
                        'code' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
                    ],
                    'font' => [
                        'bold' => false
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ],

                    ]
                ];

                $delgate = $event->sheet->getDelegate();

                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('J1:N1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('C3')->getFont()->setSize(30);
                $event->sheet->getDelegate()->getStyle('A6:N6')->getFont()->setSize(8.5);

                $event->sheet->getDelegate()->getStyle('A3:H8')->applyFromArray($styleArray);

                // $event->sheet->getDelegate()->getStyle('A1:'.$delgate->getHighestColumn().''.$delgate->getHighestRow())->applyFromArray($styleArray);
            },
        ];
    }
    public static function beforeWriting(BeforeWriting $event)
    {
        //
    }
}
