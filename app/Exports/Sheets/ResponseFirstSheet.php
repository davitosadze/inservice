<?php

namespace App\Exports\Sheets;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ResponseFirstSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
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
        return Response::whereBetween("created_at", [$from, $to])->get();
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
            $response->exact_location,
            $response->job_description,
            $response->requisites,
            $response->time,
            $response->inventory_number,
            $response->performer?->name,
            $response->date,
            $response->systemOne?->name,
            $response->systemTwo?->name,
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
            "დანადგარის ლოკაციის ზუსტი აღწერა",
            "ხარვეზის გამოსწორების მიზენით ჩატარებული სამუშაოების დეტალური აღწერა",
            "დეფექტური აქტ(ებ)ის რეკვიზიტები",
            "ფილიალში გამოცხადების დრო",
            "ინვენტარის ნომერი/აგრეგატის უნიკალური კოდი (არსებობის შემთხვევაში)",
            "შემსრულებელი",
            "თარიღი",
            "სისტემა 1",
            "სისტემა 2"
        ];
    }
}
