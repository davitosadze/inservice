<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchaser;
use App\Models\Category;

class SpecialAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $model = Purchaser::with(['specialAttributes'])->firstOrNew(['id' => $request->purchaser]);
        

        $additional = [
            'categories' => Category::with(['category_attributes.category'])->get()->toArray()
        ];
        $setting = [
            'url' => [
                'request' => 
                    [
                        'destroy' => route('api.purchasers.special-attributes.destroy', 
                            ['purchaser' => $request->purchaser, 'special_attribute' => '__delete__']
                        ),
                        'exit' => route('purchasers.edit', ['purchaser' => "new"])
                    ]
            ]
        ];

        return view('purchasers.special_attrs', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
