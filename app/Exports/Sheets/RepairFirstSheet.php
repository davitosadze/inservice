<?php

namespace App\Exports\Sheets;

use App\Models\Repair;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RepairFirstSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents
{

    private $from;
    private $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $from = $this->from;
        $to = $this->to;
        return Repair::whereBetween("created_at", [$from, $to])->get();
    }

    public function title(): string
    {
        return 'All';
    }
    public function map($response): array
    {

        return [
            $response->id,
            $response->region?->name,
            $response->name,
            $response->subject_address,
            $response->subject_name,
            $response->content,
            $response->repairDevice?->name,
            $response->act?->location?->name,
            $response->act?->note,
            $response->act?->uuid,
            $response->time,
            $response->act?->inventory_code,
            $response->performer?->name,
            $response->date,

        ];
    }


    public function headings(): array
    {
        return [
            "id",
            "რეგიონი",
            "კლიენტი",
            "მისამართი",
            "ობიექტი",
            "შინაარსი",
            "რემონტის მოწყობილობა",
            "დანადგარის ლოკაციის ზუსტი აღწერა",
            "ხარვეზის გამოსწორების მიზენით ჩატარებული სამუშაოების დეტალური აღწერა",
            "დეფექტური აქტ(ებ)ის რეკვიზიტები",
            "ფილიალში გამოცხადების დრო",
            "ინვენტარის ნომერი/აგრეგატის უნიკალური კოდი (არსებობის შემთხვევაში)",
            "შემსრულებელი",
            "თარიღი",

        ];
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:O1'; // Assuming your headings are in A1:P1
                foreach (range('A', 'P') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }

                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // white color text
                        'size' => 12, // Font size in points

                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '008000'], // green background color
                    ],

                ]);
            },
        ];
    }
}
