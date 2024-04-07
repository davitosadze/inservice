<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

class RegionController extends Controller
{

    public function index()
    {
        $this->authorize('viewAny', Region::class);

        $additional = [];
        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "name", 'headerName' => 'რეგიონი', "valueGetter" => 'data.name'],
            ],
            'url' => [
                'request' =>
                [
                    'index' => route('api.regions.index'),
                    'show' => route('regions.show', ['region' => "new"]),
                    'edit' => route('regions.edit', ['region' => "new"]),
                    'destroy' => route('api.regions.destroy', ['region' => "__delete__"])
                ]
            ],

        ];

        return view('regions.index', ['model' => Region::orderBy('id', 'desc')->get(), 'additional' => $additional, 'setting' => $setting]);
    }


    public function store(Request $request)
    {
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? $this->authorize('update', Region::find($request->id)) : $this->authorize('create', Region::class);

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

            $model = Region::firstOrNew(['id' => $request->id]);
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


    public function edit($id)
    {

        $model = Region::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }

        $additional = [];

        $setting = ['url' => ['request' => ['index' => route('regions.index')]]];

        return view('regions.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }
}
