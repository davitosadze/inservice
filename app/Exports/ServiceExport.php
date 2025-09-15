<?php

namespace App\Exports;

use App\Exports\Sheets\ServiceChartSheet;
use App\Exports\Sheets\ServiceFirstSheet;
use App\Exports\Sheets\ServicePercentageSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ServiceExport implements WithMultipleSheets
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

        $sheets[] = new ServiceFirstSheet($from, $to);
        $sheets[] = new ServiceChartSheet($from, $to);
        $sheets[] = new ServicePercentageSheet($from, $to);
        return $sheets;
    }
}
