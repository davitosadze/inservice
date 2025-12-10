<?php

namespace App\Exports\Sheets;

use App\Models\Response;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ResponseFirstSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, WithEvents, WithChunkReading
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

    public function query()
    {
        $query = Response::query()
            ->with(['region', 'performer', 'systemOne', 'systemTwo', 'act.location'])
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
    public function map($response): array
    {

        return [
            $response->id,
            $response->region?->name,
            $response->name,
            $response->subject_address,
            $response->subject_name,
            $response->content,
            $response->act?->location?->name,
            $response->act?->note,
            $response->act?->uuid,
            $response->time,
            $response->act?->inventory_code,
            $response->performer?->name,
            $response->date,
            $response->systemOne?->name,
            $response->systemTwo?->name,
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
            "დანადგარის ლოკაციის ზუსტი აღწერა",
            "ხარვეზის გამოსწორების მიზენით ჩატარებული სამუშაოების დეტალური აღწერა",
            "დეფექტური აქტ(ებ)ის რეკვიზიტები",
            "ფილიალში გამოცხადების დრო",
            "ინვენტარის ნომერი/აგრეგატის უნიკალური კოდი (არსებობის შემთხვევაში)",
            "შემსრულებელი",
            "თარიღი",
            "სისტემა 1",
            "სისტემა 2"
        ];
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:O1'; // Assuming your headings are in A1:P1
                foreach (range('A', 'P') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }

                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // white color text
                        'size' => 12, // Font size in points

                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '008000'], // green background color
                    ],

                ]);
            },
        ];
    }
}
