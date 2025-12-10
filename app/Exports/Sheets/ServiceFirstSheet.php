<?php

namespace App\Exports\Sheets;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ServiceFirstSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, WithEvents, WithChunkReading
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

    public function query()
    {
        $query = Service::query()
            ->with(['region', 'performer', 'act', 'user', 'purchaser'])
            ->whereBetween("created_at", [$this->from, $this->to]);

        // Apply filters from AG Grid
        foreach ($this->filters as $field => $filterData) {
            $filterValue = $filterData['filter'] ?? null;
            $type = $filterData['type'] ?? 'contains';

            if ($filterValue === null || $filterValue === '') {
                continue;
            }

            // Map AG Grid field to database column
            $dbField = $this->fieldMapping[$field] ?? $field;

            // Handle nested fields (like region.name, performer.name)
            if (strpos($dbField, '.') !== false) {
                $parts = explode('.', $dbField);
                $relation = $parts[0];
                $column = $parts[1];

                $query->whereHas($relation, function ($q) use ($column, $filterValue, $type) {
                    $this->applyFilter($q, $column, $filterValue, $type);
                });
            } else {
                // Direct field filter
                $this->applyFilter($query, $dbField, $filterValue, $type);
            }
        }

        return $query;
    }

    public function chunkSize(): int
    {
        return 500;
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

    public function title(): string
    {
        return 'All';
    }

    public function map($service): array
    {
        return [
            $service->id,
            $service->region?->name,
            $service->name,
            $service->subject_address,
            $service->subject_name,
            $service->content,
            $service->exact_location,
            $service->job_description,
            $service->requisites,
            $service->time,
            $service->inventory_number,
            $service->performer?->name,
            $service->date,
            $service->status,
            $service->device_type,
            $service->estimated_arrival_time,
            $service->act?->note,
        ];
    }

    public function headings(): array
    {
        return [
            "id",
            "რეგიონი",
            "კლიენტი",
            "მისამართი",
            "ობიექტი",
            "შინაარსი",
            "ზუსტი ლოკაცია",
            "სამუშაოს აღწერა",
            "რეკვიზიტები",
            "ფილიალში გამოცხადების დრო",
            "ინვენტარის ნომერი",
            "შემსრულებელი",
            "თარიღი",
            "სტატუსი",
            "მოწყობილობის ტიპი",
            "მოსალოდნელი ჩამოსვლის დრო",
            "შენიშვნა"
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:Q1';
                foreach (range('A', 'Q') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }

                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '008000'],
                    ],
                ]);
            },
        ];
    }
}
