<?php

namespace App\Exports;

use App\Exports\Sheets\ResponseBySystemExport;
use App\Exports\Sheets\ResponseChartSheet;
use App\Exports\Sheets\ResponseFirstSheet;
use App\Exports\Sheets\ResponsePercentageSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ResponseExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $from;
    private $to;
    private $filters;

    public function __construct($from, $to, $filters = [])
    {
        $this->from = $from;
        $this->to = $to;
        $this->filters = $filters;
    }


    public function sheets(): array
    {
        $sheets[] = new ResponseFirstSheet($this->from, $this->to, $this->filters);
        $sheets[] = new ResponseChartSheet($this->from, $this->to, $this->filters);
        $sheets[] = new ResponseBySystemExport($this->from, $this->to, $this->filters);
        $sheets[] = new ResponsePercentageSheet($this->from, $this->to, $this->filters);
        return $sheets;
    }
}
