<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Invoice;
use App\Models\Category;
use App\Models\Purchaser;

use App\Exports\InvoiceExport;
use App\Exports\InvoiceNewFormExport;
use App\Exports\ReservingExport;
use App\Models\Option;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;
use Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $this->authorize('viewAny', Invoice::class);

        $additional = [];
        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.uuid', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "title", 'headerName' => 'დასახელება'],
                ['field' => "purchaser_name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.purchaser.name'],
                ['field' => "purchaser_subj_name", 'headerName' => 'ობიექტის სახ.', "valueGetter" => 'data.purchaser.subj_name'],
                ['field' => "purchaser_address", 'headerName' => 'ობიექტის მისამართი.', "valueGetter" => 'data.purchaser.subj_address'],
                ['field' => "user", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user.name'],
                ['field' => "purchaser_address", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
                ['headerName' => 'ჯამი', "valueGetter" => "data.full_price"],
            ],
            'model' => "invoices",
            'url' => [
                'request' =>
                ['index' => route('api.invoices.index'), 'edit' => route('invoices.edit', ['invoice' => "new"]), 'destroy' => route('api.invoices.destroy', ['invoice' => '__delete__'])],
                'nested' => [
                    'excel' => route(
                        'invoices.excel',
                        ['id' => '__id__']
                    ),
                    'pdf' => route(
                        'invoices.pdf',
                        ['id' => '__id__']
                    )
                ]
            ],
            'is_table_advanced' => true
        ];

        return view('invoices.index', ['additional' => $additional, 'setting' => $setting]);
    }

    public function exportNew(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        $keyword = preg_replace('/[^\p{L}]+/u', '', $request->get('purchaser'));

        $purchasers = Purchaser::get()->where('formattedName', $keyword)->pluck('id');

        $invoices = Invoice::with(['purchaser', 'category_attributes.category'])
            ->whereIn('purchaser_id', $purchasers)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        // Log the invoice data to check the model structure
        Log::info("Invoices: " . $invoices->toJson());

        $zip = new ZipArchive;
        $zipFileName = 'invoices_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $openStatus = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openStatus !== true) {
            return response()->json([
                'error' => "Zip creation failed. Code: $openStatus"
            ], 500);
        }

        foreach ($invoices as $invoice) {

            $model = $invoice->toArray();
            $fileName = "ინვოისი_" . $model['uuid'] . '.xlsx';

            // Check the model to make sure the data is valid
            Log::info("Model Data for Invoice: " . json_encode($model));

            $templatePath = storage_path('app/newform.xlsx');

            // Check if template exists
            if (!file_exists($templatePath)) {
                Log::error("Template file not found: " . $templatePath);
                continue;
            }

            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            $summary = 0;
            $startRow = 14;

            foreach ($model["category_attributes"] as $index => $item) {
                $name = $item["pivot"]["title"];
                $unit = $item["item"];
                $qty = $item["pivot"]["qty"];
                $material_price = $item["pivot"]["price"];
                $service_price = $item["service_price"];
                $sum = $item["pivot"]["calc"];
                $summary += $sum;

                $sheet->setCellValue("C" . ($startRow + $index), $name);
                $sheet->setCellValue("E" . ($startRow + $index), $unit);
                $sheet->setCellValue("F" . ($startRow + $index), $qty);
                $sheet->setCellValue("G" . ($startRow + $index), $material_price);
                $sheet->setCellValue("H" . ($startRow + $index), $service_price);
                $sheet->setCellValue("I" . ($startRow + $index), $sum);
            }

            $purchaser = $model["purchaser"];
            $sheet->setCellValue('D5', $model["created_at"]);
            $sheet->setCellValue('D7', $purchaser["name"]);
            $sheet->setCellValue('D8', $purchaser["subj_address"]);
            $sheet->setCellValue('D9', $model["uuid"]);
            $sheet->setCellValue('D10', $model["title"]);
            $sheet->setCellValue('I24', $summary);

            // Save to a temporary file (without ZIP temporarily)
            $tempFile = storage_path("app/temp_$fileName");
            $writer = new Xlsx($spreadsheet);

            Log::info("Saving Excel file to: " . $tempFile);

            $writer->save($tempFile);

            if (file_exists($tempFile)) {
                Log::info("Excel file saved successfully: " . $tempFile);
            } else {
                Log::error("Failed to save Excel file: " . $tempFile);
            }

            // Add to the zip archive (after verifying the file save)
            if (file_exists($tempFile)) {
                if ($zip->addFile($tempFile, $fileName)) {
                    Log::info("Successfully added file to zip: " . $fileName);
                } else {
                    Log::error("Failed to add file to zip: " . $fileName);
                }
            }
        }

        // Close the zip
        $zip->close();

        // Check for zip existence
        Log::info("ZIP file created at: " . $zipPath);

        if (file_exists($zipPath)) {
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', "ფილტრში ინვოისები არ არსებობს");
        }
    }


    public function pdf(Request $request, $id)
    {

        $model = Invoice::with(['purchaser', 'category_attributes.category', 'user', 'parent'])->firstOrNew(['id' => $id])->toArray();
        $user = User::find($model["user"]["id"]);

        $name = "ინვოისი " . $model['uuid'] . '.pdf';

        $pdf = PDF::setOptions(['isRemoteEnabled' => true, 'dpi' => 150, 'defaultFont' => 'sans-serif'])->loadView('invoices.pdf', ['model' => $model, 'user' => $user]);

        if ($request->open) {
            return $pdf->stream($name);
        }
        return $pdf->download($name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $model = Invoice::with(['purchaser', 'category_attributes.category', 'parent'])->firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        } else {
            $model = $model->toArray();
        }

        $additional = [
            'purchasers' => Purchaser::where('single', '!=', 1)->with(['specialAttributes'])->get()->toArray(),
            'categories' => Category::with(['category_attributes.category'])->get()->toArray(),
            'price_increase' => Option::first() ? Option::first()->price_increase : 0
        ];
        $setting = [
            'url' => [
                'request' =>
                ['index' => route('api.invoices.index'), 'edit' => route('invoices.edit', ['invoice' => "new"])],
                'nested' => [
                    'edit' => route('purchasers.edit', ['purchaser' => "new"]),
                    'destroy' => route(
                        'api.invoices.destroy_attribute',
                        ['id' => '__id__']
                    )
                ]
            ]
        ];


        return view('invoices.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
