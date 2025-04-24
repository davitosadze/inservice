<?php

namespace App\Exports\Sheets;

use App\Models\Repair;
use App\Models\Response;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart as ChartChart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class RepairChartSheet implements WithCharts, WithTitle
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
        return 'Chart';
    }
    /**
     * @return \Illuminate\Support\Collection
     */


    public function charts()
    {
        $from = $this->from;
        $to = $this->to;

        $groupped = Repair::select('name')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('name')
            ->get();

        $lastRow = $groupped->count() + 1;
        $label      = [
            new DataSeriesValues('String', 'Counts!$A$1', null, 1)
        ];
        $categories = [new DataSeriesValues('String', "Counts!\$A\$2:\$A\${$lastRow}", null, 4)];
        $values     = [new DataSeriesValues('Number', "Counts!\$B\$2:\$B\${$lastRow}", null, 4)];

        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, \count($values) - 1),
            $label,
            $categories,
            $values
        );


        $layout = new Layout();
        $layout->setShowPercent(true);
        $layout->setShowCatName(true);

        $plot   = new PlotArea($layout, [$series]);

        $legend = new Legend();
        $chart  = new ChartChart('Chart', new Title('Chart'), $legend, $plot);
        $chart->setTopLeftPosition('A1');
        $chart->setBottomRightPosition('I25');



        return $chart;
    }
}
