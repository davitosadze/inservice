<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Invoice;
use App\Models\Category;
use App\Models\Purchaser;

use App\Exports\InvoiceExport;
use App\Exports\ReservingExport;
use App\Models\Option;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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

    public function excel($id)
    {
        $model = Invoice::with(['purchaser', 'category_attributes.category'])->firstOrNew(['id' => $id])->toArray();
        $name = "ინვოისი " . $model['uuid'] . '.xlsx';
        return Excel::download(new ReservingExport($model), $name);
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
