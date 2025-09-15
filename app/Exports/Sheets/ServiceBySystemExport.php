<?php

namespace App\Exports\Sheets;

use App\Models\Service;
use App\Models\System;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ServiceBySystemExport implements WithTitle, WithHeadings, FromCollection
{

    private $from;
    private $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }


    public function title(): string
    {
        return 'Systems';
    }



    public function collection()
    {

        $from = $this->from;
        $to = $this->to;
        $services = Service::select('name')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('name')
            ->get();


        $data = $services->map(function ($service) {
            $systems = System::where("parent_id", NULL)->orderBy('id', 'desc')->get();
            $data = [$service->name];

            $servicesCount = Service::where('name', $service->name)->count();
            foreach ($systems as $system) {
                $count = Service::where('name', $service->name)->where('system_one', $system->id)->count();
                $percentage = number_format(($count / $servicesCount) * 100, 0);
                $data[] = $percentage ? $percentage . "%" : "Null";
            }
            return $data;
        });

        return $data;
    }


    public function headings(): array
    {
        $headings = ["კლიენტი"];
        $systems = System::where("parent_id", NULL)->orderBy('id', 'desc')->get();
        foreach ($systems as $system) {
            $headings[] = $system->name;
        }
        return $headings;
    }
}