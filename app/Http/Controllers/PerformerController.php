<?php

namespace App\Http\Controllers;

use App\Models\Performer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

class PerformerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Performer::class);

        $additional = [];
        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "name", 'headerName' => 'რეგიონი', "valueGetter" => 'data.name'],
            ],
            'url' => [
                'request' =>
                [
                    'index' => route('api.performers.index'),
                    'show' => route('performers.show', ['performer' => "new"]),
                    'edit' => route('performers.edit', ['performer' => "new"]),
                    'destroy' => route('api.performers.destroy', ['performer' => "__delete__"])
                ]
            ]
        ];

        return view('performers.index', ['model' => Performer::orderBy('id', 'desc')->get(), 'additional' => $additional, 'setting' => $setting]);
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
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? $this->authorize('update', Performer::find($request->id)) : $this->authorize('create', Performer::class);

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required'
                ],
            ]);

            if ($validator->fails()) {
                $result['errs'] = $validator->errors()->all();
                $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

                return response()->json($result);
            };

            $model = Performer::firstOrNew(['id' => $request->id]);
            $model->fill($request->all());

            if (!$model->user) {
                $model->user()->associate(auth()->user());
            }

            $model->save();

            DB::commit();

            $result['success'] = true;
            $result['result'] = $model;
            $result['status'] = Response::HTTP_CREATED;
            $result = Arr::prepend($result, 'მონაცემები განახლდა წარმატებით', 'statusText');
        } catch (Exception $e) {
            $result = Arr::prepend($result, 'შეცდომა, მონაცემების განახლებისას', 'statusText');
            $result = Arr::prepend($result, Arr::prepend($result['errs'], 'გაურკვეველი შეცდომა! ' . $e->getMessage()), 'errs');

            DB::rollBack();
        }

        return response()->json($result, Response::HTTP_CREATED);
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

        $model = Performer::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }

        $additional = [];

        $setting = ['url' => ['request' => ['index' => route('performers.index')]]];

        return view('performers.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
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
    }
}
