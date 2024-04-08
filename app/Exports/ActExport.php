<?php

namespace App\Exports;

use App\Models\Act;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Writer;

class ActExport implements WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    const TEMPLATE_FILE = 'act.xlsx';

    protected $act;
    public function __construct($act)
    {
        $this->act = $act;
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $this->configureSheet($event->writer);

                $sheet = $event->writer->getSheetByIndex(0);
                $this->populateSheet($sheet);
            },
        ];
    }

    protected function configureSheet(Writer $writer)
    {
        $templateFile = new LocalTemporaryFile(storage_path(self::TEMPLATE_FILE));
        $writer->reopen($templateFile, Excel::XLSX);
    }

    protected function populateSheet($sheet)
    {
        $act = $this->act;
        $heading = 'შპს "ინსერვისი" აქტი №' . $act->uuid;
        $sheet->setCellValue("A1", $heading);

        $actDay = Carbon::parse($act->created_at)->day;
        $actYear = Carbon::parse($act->created_at)->year % 10;
        $yearFormatted = "202" . $actYear . " წ.";
        $actMonth = Carbon::parse($act->created_at)->month;


        $sheet->setCellValue("B7", $act->deviceType?->name);
        $sheet->setCellValue("B8", $act->deviceBrand?->name);
        $sheet->setCellValue("B9", $act->device_model);
        $sheet->setCellValue("B10", $act->inventory_code);

        // Object
        $sheet->setCellValue("F2", $act->response?->name);
        $sheet->setCellValue("F3", $act->response?->subject_address);
        $sheet->setCellValue("F4", $act->response?->created_at);
        $sheet->setCellValue("F5", $act->exact_location);
        $sheet->setCellValue("E7", $act->note);

        // Date
        $sheet->setCellValue("F1", $actDay);
        $sheet->setCellValue("G1", $actMonth);
        $sheet->setCellValue("H1", $yearFormatted);

        // Performer
        $sheet->setCellValue("G12", $act->response?->performer?->name);

        // Purchaser
        $sheet->setCellValue("C12", $act->response?->subject_name);

        $client = $act->position . " , " . $act->client_name;
        $sheet->setCellValue("C14", $client);
    }
}
