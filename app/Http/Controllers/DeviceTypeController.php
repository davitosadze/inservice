<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

class DeviceTypeController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', DeviceType::class);

        $additional = [];
        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "name", 'headerName' => 'მოწყობილობის სახელი', "valueGetter" => 'data.name'],
            ],
            'url' => [
                'request' =>
                [
                    'index' => route('api.device-types.index'),
                    'show' => route('device-types.show', ['device_type' => "new"]),
                    'edit' => route('device-types.edit', ['device_type' => "new"]),
                    'destroy' => route('api.device-types.destroy', ['device_type' => "__delete__"])
                ]
            ],

        ];

        return view('device-types.index', ['model' => DeviceType::orderBy('id', 'desc')->get(), 'additional' => $additional, 'setting' => $setting]);
    }


    public function store(Request $request)
    {
        $result = ['status' => Response::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = $request->id ? $this->authorize('update', DeviceType::find($request->id)) : $this->authorize('create', DeviceType::class);

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

            $model = DeviceType::firstOrNew(['id' => $request->id]);
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

        $model = DeviceType::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }

        $additional = [];

        $setting = ['url' => ['request' => ['index' => route('device-types.index')]]];

        return view('device-types.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting]);
    }
}
