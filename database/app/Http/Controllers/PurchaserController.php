<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchaser;

class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->authorize('viewAny', Purchaser::class);

        $additional = [];
        $setting = [
            'columns' => [['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'], ['field' => "name", 'headerName' => 'საიდენთიპიკაციო კოდი', "valueGetter" => 'data.identification_num'], ['field' => "name", 'headerName' => 'კლიენტის სახელი'], ['field' => "subj_name", 'headerName' => 'დამატებითი სახელი'], ['field' => "subj_address", 'headerName' => 'კლიენტის მისამართი'] ],
            'url' => [
                'request' => 
                    ['index' => route('api.purchasers.index'), 'edit' => route('purchasers.edit', ['purchaser' => "new"]), 'destroy' => route('api.purchasers.destroy', ['purchaser' => "__delete__"])]
            ]
        ];
        return view('purchasers.index', ['additional' => $additional, 'setting' => $setting]);
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
        $model = Purchaser::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);
        
        $additional = [];
        $setting = [
            'url' => [
                'request' => 
                [
                    'attrs' => route('purchasers.special-attributes.index', ['purchaser' => 'new']),
                    'index' => route('api.purchasers.index')
                ]
            ]
        ];

        return view('purchasers.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
