<?php

namespace App\Exports;

use App\Exports\Sheets\RepairChartSheet;
use App\Exports\Sheets\RepairFirstSheet;
use App\Exports\Sheets\RepairPercentageSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RepairExport implements WithMultipleSheets
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
        $sheets[] = new RepairFirstSheet($this->from, $this->to, $this->filters);
        $sheets[] = new RepairChartSheet($this->from, $this->to, $this->filters);
        $sheets[] = new RepairPercentageSheet($this->from, $this->to, $this->filters);
        return $sheets;
    }
}
