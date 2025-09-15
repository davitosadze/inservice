<?php

namespace App\Exports;

use App\Exports\Sheets\ResponseBySystemExport;
use App\Exports\Sheets\ResponseChartSheet;
use App\Exports\Sheets\ResponseFirstSheet;
use App\Exports\Sheets\ResponsePercentageSheet;
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

        $sheets[] = new ResponseFirstSheet($from, $to);
        $sheets[] = new ResponseChartSheet($from, $to);
        $sheets[] = new ResponseBySystemExport($from, $to);
        $sheets[] = new ResponsePercentageSheet($from, $to);
        return $sheets;
    }
}
