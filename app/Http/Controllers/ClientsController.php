<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Client::class);

        $additional = [];
        $setting = [
            'columns' => [['field' => "client_identification_code", 'headerName' => 'კლიენტის მაიდენთიფიცირებელი კოდი'], ['field' => "client_name", 'headerName' => 'კლიენტის სახელი'],  ['headerName' => 'მომსახურების შიდა სახელი', 'field' => "service_name"], ['headerName' => 'კონტრაქტის მომსახურების ტიპი', 'field' => "contract_service_type"], ['headerName' => 'კონტრაქტის სტატუსი', 'field' => "contract_status"]],
            'model' => ['target' => 'Client'],
            'url' => [
                'request' =>
                [
                    'index' => route('api.clients.index'),
                    'edit' => route('clients.edit', ['client' => "new"]), 'destroy' => route('api.clients.destroy', ['client' => "__delete__"])
                ],
            ]
        ];

        return view('clients.index', ['additional' => $additional, 'setting' => $setting]);
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
        $model = Client::with(['expenses' => function ($query) {
            $query->with('media');
        }])->firstOrNew(['id' => $id]);
        // return $model;
        $this->authorize('view', $model);

        $additional = [];
        $setting = [

            'columns' => [['field' => "client_name"]],
            'url' => [
                'request' =>
                [
                    'index' => route('api.clients.index')
                ],
            ]
        ];

        return view('clients.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
