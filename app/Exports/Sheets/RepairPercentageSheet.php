<?php

namespace App\Exports\Sheets;

use App\Models\Repair;
use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RepairPercentageSheet implements FromCollection, WithHeadings, WithTitle
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
        $repairs = Repair::select('name')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(*) as count')
            ->groupBy('name')
            ->get();

        $data = $repairs->map(function ($repair) {
            return [
                $repair->name,
                $repair->count,
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
