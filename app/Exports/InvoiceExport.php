<?php

namespace App\Exports;

use App\Models\Invoice;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\AfterSheet;


use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class InvoiceExport extends DefaultValueBinder implements WithDrawings, FromView, WithEvents,  WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $res;


    public function __construct($res) {
        $this->res = $res;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath('./Logo-Test.png');
        $drawing->setWidth(150);
        
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(17);
        $drawing->setOffsetY(17);

        return $drawing;
    }

    public function view(): View
    {

        return view('invoices.excel', [
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
            AfterSheet::class    => function(AfterSheet $event) {

                $styleArray = [
                    'numberformat' => [
                        'code' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],

                    ]
                ];

                $delgate = $event->sheet->getDelegate();

                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('N1:Y1')->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle('E3:L8')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('N3:V8')->applyFromArray($styleArray);

                $num = (string) count($this->res['category_attributes']) + 1 + 10;

                $event->sheet->getDelegate()->getStyle('A10:Y'.$num)->applyFromArray($styleArray);

                // $event->sheet->getDelegate()->getStyle('A1:'.$delgate->getHighestColumn().''.$delgate->getHighestRow())->applyFromArray($styleArray);
            },
        ];
    }
    
    public static function beforeWriting(BeforeWriting $event) 
    {
        //
    }
}
