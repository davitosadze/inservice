<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $this->authorize('viewAny', System::class);

        $additional = [];
        $setting = [
            'columns' => [['field' => "name", 'headerName' => 'დასახელება']],
            'model' => ['target' => 'System'],
            'url' => [
                'request' =>
                [
                    'index' => route('api.systems.index'),
                    'edit' => route('systems.edit', ['system' => "new"]), 'destroy' => route('api.systems.destroy', ['system' => "__delete__"])
                ],

            ]
        ];

        return view('systems.index', ['additional' => $additional, 'setting' => $setting]);
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

        $model = System::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);


        $additional = [];
        $setting = [
            'columns' => [['field' => "name"]],
            'url' => [
                'request' =>
                [
                    'index' => route('api.systems.index')
                ],
            ]
        ];

        return view('systems.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
