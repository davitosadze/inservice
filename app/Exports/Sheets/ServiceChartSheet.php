<?php

namespace App\Exports\Sheets;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart as ChartChart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ServiceChartSheet implements WithCharts, WithTitle
{
    private $from;
    private $to;
    private $filters;

    /**
     * Map AG Grid field names to actual database columns/relations
     */
    private $fieldMapping = [
        'purchaser_name' => 'name',
        'purchaser_address' => 'subject_address',
        'purchaser_subj_name' => 'subject_name',
        'region_name' => 'region.name',
        'user' => 'user.name',
        'performer_name' => 'performer.name',
        'content' => 'content',
        'job_time' => 'job_time',
        'title' => 'id',
        'technical_time' => 'purchaser.technical_time',
        'cleaning_time' => 'purchaser.cleaning_time',
    ];

    public function __construct($from, $to, $filters = [])
    {
        $this->from = $from;
        $this->to = $to;
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Chart';
    }

    public function charts()
    {
        $query = Service::select('name')
            ->whereBetween('created_at', [$this->from, $this->to]);

        // Apply filters
        foreach ($this->filters as $field => $filterData) {
            $filterValue = $filterData['filter'] ?? null;
            $type = $filterData['type'] ?? 'contains';

            if ($filterValue === null || $filterValue === '') {
                continue;
            }

            $dbField = $this->fieldMapping[$field] ?? $field;

            if (strpos($dbField, '.') !== false) {
                $parts = explode('.', $dbField);
                $relation = $parts[0];
                $column = $parts[1];

                $query->whereHas($relation, function ($q) use ($column, $filterValue, $type) {
                    $this->applyFilter($q, $column, $filterValue, $type);
                });
            } else {
                $this->applyFilter($query, $dbField, $filterValue, $type);
            }
        }

        $groupped = $query->groupBy('name')->get();

        $lastRow = $groupped->count() + 1;
        $label = [
            new DataSeriesValues('String', 'Counts!$A$1', null, 1)
        ];
        $categories = [new DataSeriesValues('String', "Counts!\$A\$2:\$A\${$lastRow}", null, 4)];
        $values = [new DataSeriesValues('Number', "Counts!\$B\$2:\$B\${$lastRow}", null, 4)];

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

        $plot = new PlotArea($layout, [$series]);

        $legend = new Legend();
        $chart = new ChartChart('Chart', new Title('Chart'), $legend, $plot);
        $chart->setTopLeftPosition('A1');
        $chart->setBottomRightPosition('I25');

        return $chart;
    }

    private function applyFilter($query, $column, $value, $type)
    {
        switch ($type) {
            case 'contains':
                $query->where($column, 'LIKE', '%' . $value . '%');
                break;
            case 'equals':
                $query->where($column, '=', $value);
                break;
            case 'startsWith':
                $query->where($column, 'LIKE', $value . '%');
                break;
            case 'endsWith':
                $query->where($column, 'LIKE', '%' . $value);
                break;
            case 'notContains':
                $query->where($column, 'NOT LIKE', '%' . $value . '%');
                break;
            default:
                $query->where($column, 'LIKE', '%' . $value . '%');
        }
    }
}
