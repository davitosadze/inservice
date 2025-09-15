<?php

namespace App\Exports\Sheets;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ServicePercentageSheet implements FromCollection, WithHeadings, WithTitle
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
        return 'Counts';
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $from = $this->from;
        $to = $this->to;
        $services = Service::select('name')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(*) as count')
            ->groupBy('name')
            ->get();

        $data = $services->map(function ($service) {
            return [
                $service->name,
                $service->count,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            "კლიენტი",
            "რაოდენობა"
        ];
    }
}