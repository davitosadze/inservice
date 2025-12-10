<?php

namespace App\Exports\Sheets;

use App\Models\Response;
use App\Models\System;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ResponseBySystemExport implements WithTitle, WithHeadings, FromCollection
{

    private $from;
    private $to;
    private $filters;
    private $systems;

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
        'job_time' => 'time',
        'title' => 'id',
    ];

    public function __construct($from, $to, $filters = [])
    {
        $this->from = $from;
        $this->to = $to;
        $this->filters = $filters;
        $this->systems = System::where("parent_id", NULL)->orderBy('id', 'desc')->get();
    }


    public function title(): string
    {
        return 'Systems';
    }

    public function collection()
    {
        // Build a single aggregated query
        $query = Response::query()
            ->select('name', 'system_one', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$this->from, $this->to]);

        // Apply filters
        foreach ($this->filters as $field => $filterData) {
            $filterValue = $filterData['filter'] ?? null;
            $type = $filterData['type'] ?? 'contains';

            if ($filterValue === null || $filterValue === '') {
                continue;
            }

            // Map AG Grid field to database column
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

        // Get all counts in one query grouped by name and system
        $results = $query->groupBy('name', 'system_one')->get();

        // Build lookup table: name -> system_id -> count
        $countsByNameAndSystem = [];
        $totalsByName = [];

        foreach ($results as $row) {
            $name = $row->name;
            $systemId = $row->system_one;

            if (!isset($countsByNameAndSystem[$name])) {
                $countsByNameAndSystem[$name] = [];
                $totalsByName[$name] = 0;
            }

            $countsByNameAndSystem[$name][$systemId] = $row->count;
            $totalsByName[$name] += $row->count;
        }

        // Build result data
        $data = collect();
        foreach ($countsByNameAndSystem as $name => $systemCounts) {
            $row = [$name];
            $total = $totalsByName[$name];

            foreach ($this->systems as $system) {
                $count = $systemCounts[$system->id] ?? 0;
                $percentage = $total > 0 ? number_format(($count / $total) * 100, 0) : 0;
                $row[] = $percentage ? $percentage . "%" : "Null";
            }

            $data->push($row);
        }

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
        $headings = ["კლიენტი"];
        foreach ($this->systems as $system) {
            $headings[] = $system->name;
        }
        return $headings;
    }
}
