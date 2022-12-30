<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchaser;
use App\Models\Evaluation;
use App\Models\Category;

use App\Exports\ReservingExport;
use Maatwebsite\Excel\Facades\Excel;


use Barryvdh\DomPDF\Facade\Pdf;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->authorize('viewAny', Evaluation::class);

        $additional = [];
        $setting = [
            'columns' => [
                ['field' => "num", 'headerName' => '№', "valueGetter" => 'data.uuid', "flex" => 0.7, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'], 
                ['field' => "title", "valueGetter" => 'data.title', 'headerName' => 'დასახელება', 'minWidth' => 350, 'tooltipField' => 'title', 'editable' => true], 
                ['field' => "purchaser_name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.purchaser.name'],
                ['field' => "purchaser_subj_name", 'headerName' => 'ობიექტის სახ.', "valueGetter" => 'data.purchaser.subj_name'],
                ['field' => "purchaser_address", 'headerName' => 'ობიექტის მისამართი.', "valueGetter" => 'data.purchaser.subj_address'],
                ['field' => "purchaser_address", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
                ['field' => "user", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user.name'],
            ],
            'url' => [
                'request' => 
                    ['index' => route('api.evaluations.index'), 'edit' => route('evaluations.edit', ['evaluation' => "new"]) ,'destroy' => route('api.evaluations.destroy', ['evaluation' => '__delete__'])],
                'nested' => [
                    'excel' => route('evaluations.excel', ['id' => '__id__']
                    ),
                    'pdf' => route('evaluations.pdf', ['id' => '__id__']
                    )
                ]
            ],
            'is_table_advanced' => true
        ];
        
        return view('requests.index', ['additional' => $additional, 'setting' => $setting]);
    }

    public function excel ($id) {
        $model = Evaluation::with(['purchaser', 'category_attributes.category'])->firstOrNew(['id' => $id])->toArray();
        return Excel::download(new ReservingExport($model), 'users.xlsx');
    }

    public function pdf ($id) {

        $model = Evaluation::with(['purchaser', 'category_attributes.category'])->firstOrNew(['id' => $id])->toArray();
        $name = $model['uuid']. '.pdf';

        $pdf = PDF::setOptions(["isPhpEnabled" => true, 'isRemoteEnabled' => true, 'dpi' => 150, 'defaultFont' => 'sans-serif', 'name' => $name]);
        $pdf->loadView('requests.pdf', ['model' => $model, 'pdf' => $pdf]);
        return $pdf->stream($name);
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

        $model = Evaluation::with(['purchaser', 'category_attributes.category'])->firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        } else {
            $model = $model->toArray();
        }

        $additional = [
            'purchasers' => Purchaser::where('single', '!=', 1)->with(['specialAttributes'])->get()->toArray(),
            'categories' => Category::with(['category_attributes.category'])->get()->toArray()
        ];
        $setting = [
            'url' => [
                'request' => 
                    ['index' => route('api.evaluations.index'), 'edit' => route('evaluations.edit', ['evaluation' => "new"])],
                'nested' => [
                    'edit' => route('purchasers.edit', ['purchaser' => "new"]),
                    'destroy' => route('api.requests.destroy_attribute', ['id' => '__id__']
                    )
                ]
            ]
        ];

        //print_r($model); exit;

        return view('requests.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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


// if (!$model->id) {
// $model->setRelation('category_attributes', collect([]));
// $model->setRelation('purchaser', collect());
// $model->setRelation('pivot', collect());
// }

// $model->category_attributes->transform(function($res) use ($model) {
       
// $app = SpecialAttribute::where('category_attribute_id', $res->id)->where("purchaser_id",  $model->purchaser->id)->get();

// $res->setRelation('test', $app);
// return $res;
// });