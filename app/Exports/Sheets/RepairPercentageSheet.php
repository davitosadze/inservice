<?php

namespace App\Exports\Sheets;

use App\Models\Repair;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RepairPercentageSheet implements FromCollection, WithHeadings, WithTitle
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
        'repair_device_name' => 'repairDevice.name',
    ];

    public function __construct($from, $to, $filters = [])
    {
        $this->from = $from;
        $this->to = $to;
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Counts';
    }

    public function collection()
    {
        $query = Repair::select('name')
            ->whereBetween('created_at', [$this->from, $this->to])
            ->selectRaw('COUNT(*) as count');

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

        $repairs = $query->groupBy('name')->get();

        $data = $repairs->map(function ($repair) {
            return [
                $repair->name,
                $repair->count,
            ];
        });

        return $data;
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

    public function headings(): array
    {
        return [
            "კლიენტი",
            "რაოდენობა"
        ];
    }
}
