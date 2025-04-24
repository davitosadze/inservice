<?php

namespace App\Exports;

use App\Exports\Sheets\RepairChartSheet;
use App\Exports\Sheets\RepairFirstSheet;
use App\Exports\Sheets\RepairPercentageSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RepairExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $from;
    private $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }


    public function sheets(): array
    {
        $from = $this->from;
        $to = $this->to;

        $sheets[] = new RepairFirstSheet($from, $to);
        $sheets[] = new RepairChartSheet($from, $to);
        $sheets[] = new RepairPercentageSheet($from, $to);
        return $sheets;
    }
}
