<?php

namespace App\Exports\Sheets;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ServiceFirstSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents
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
        return Service::with('act')->whereBetween("created_at", [$from, $to])->get();
    }

    public function title(): string
    {
        return 'All';
    }
    public function map($service): array
    {

        return [
            $service->id,
            $service->region?->name,
            $service->name,
            $service->subject_address,
            $service->subject_name,
            $service->content,
            $service->exact_location,
            $service->job_description,
            $service->requisites,
            $service->time,
            $service->inventory_number,
            $service->performer?->name,
            $service->date,
            $service->status,
            $service->device_type,
            $service->estimated_arrival_time,
            $service->act?->note,
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
            "ზუსტი ლოკაცია",
            "სამუშაოს აღწერა",
            "რეკვიზიტები",
            "ფილიალში გამოცხადების დრო",
            "ინვენტარის ნომერი",
            "შემსრულებელი",
            "თარიღი",
            "სტატუსი",
            "მოწყობილობის ტიპი",
            "მოსალოდნელი ჩამოსვლის დრო",
            "შენიშვნა"
        ];
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:Q1'; // Assuming your headings are in A1:Q1
                foreach (range('A', 'Q') as $columnID) {
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