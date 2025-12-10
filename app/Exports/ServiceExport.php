<?php

namespace App\Exports;

use App\Exports\Sheets\ServiceChartSheet;
use App\Exports\Sheets\ServiceFirstSheet;
use App\Exports\Sheets\ServicePercentageSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ServiceExport implements WithMultipleSheets
{
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
        $sheets[] = new ServiceFirstSheet($this->from, $this->to, $this->filters);
        $sheets[] = new ServiceChartSheet($this->from, $this->to, $this->filters);
        $sheets[] = new ServicePercentageSheet($this->from, $this->to, $this->filters);
        return $sheets;
    }
}
