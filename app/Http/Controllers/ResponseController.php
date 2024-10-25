<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Purchaser;
use App\Models\Region;
use App\Models\Response;
use App\Models\System;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('viewAny', Response::class);



        $setting = [
            'columns' => [
                ['field' => "title", 'headerName' => '№', "valueGetter" => 'data.id', "flex" => 0.5, 'cellStyle' => ['textAlign' => 'center'], 'headerClass' => 'text-center'],
                ['field' => "region_name", 'headerName' => 'რეგიონი', "valueGetter" => 'data.region.name'],

                ['field' => "purchaser_name", 'headerName' => 'კლიენტის სახ.', "valueGetter" => 'data.purchaser.name'],
                ['field' => "purchaser_address", 'headerName' => 'ობიექტის მისამართი.', "valueGetter" => 'data.purchaser.subj_address'],
                ['field' => "purchaser_subj_name", 'headerName' => 'ობიექტის სახ.', "valueGetter" => 'data.purchaser.subj_name'],

                ['field' => "user", 'headerName' => 'მომხმარებელი', "valueGetter" => 'data.user.name'],
                ['field' => "purchaser_address", 'headerName' => 'თარიღი', "valueGetter" => 'data.created_at', 'type' => ['dateColumn', 'nonEditableColumn']],
            ],
            'model' => 'responses',
            'url' => [
                'request' =>
                [
                    'index' => route('api.responses.index', ["type" => $request->get('type')]),
                    'show' => route('responses.show', ['response' => "new"]),
                    'edit' => route('responses.edit', ['response' => "new"]),
                    'destroy' => route('api.responses.destroy', ['response' => "__delete__"])
                ]
            ],
            "table_view_enabled" => true,
            "type" => "gamarjoba"

        ];

        $additional = [];

        if (Auth::user()->roles->contains('name', 'ინჟინერი')) {
            $responses = Response::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc')
                ->whereIn("status", [1, 10])
                ->where("performer_id", Auth::user()->id);
        } else {
            $responses = Response::with(['user', 'purchaser', 'region'])->orderBy('id', 'desc');
        }

        if ($request->get("type") == "done") {
            $responses = $responses->where("status", 3)
                ->orWhere("status", 0)
                ->get();
        } else {
            $responses = $responses->whereIn("status", [1, 2, 10])
                ->get();
        }

        return view('responses.index', ['additional' => $additional, 'setting' => $setting, 'responses' => $responses]);
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

        $validator = Validator::make($request->all(), [

            'subject_name' => ['required'],
            'subject_address' => ['required'],
            'region_id' => ['required'],
            'performer_id' => ['required'],
            'name' => ['required'],
            'identification_num' => ['required'],
            'system_one' => ['required'],
        ]);

        if ($validator->fails()) {
            $result['errs'] = $validator->errors()->all();
            $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';

            return response()->json($result);
        };

        $result = [
            'status' => HttpResponse::HTTP_FORBIDDEN,
            'success' => false,
            'errs' => [],
            'result' => [],
            'statusText' => ""
        ];

        $response = $request->id ? $this->authorize('update', Response::find($request->id)) : $this->authorize('create', Response::class);

        DB::beginTransaction();

        try {


            $model = Response::firstOrNew(['id' => $request->id]);

            $purchaser = json_decode($request->purchaser);

            $model->fill($request->all());
            $model->purchaser_id = $purchaser->id;

            if (!$request->get('system_two')) {
                $model->system_two = NULL;
            }

            if (!$model->user) {
                $model->user()->associate(auth()->user());
            }

            if ($model->status == 2) {
                $model->status = 3;
                $model->act()->update([
                    "note" => $request->get("job_description"),
                    "inventory_code" => $request->get("inventory_number"),
                    "uuid" => $request->get("requisites"),
                ]);
            }

            if (!$model->id) {
                $model->status = 1;
                $model->save();

                if ($model->performer?->expo_token) {
                    $messages = [
                        new ExpoMessage([
                            'title' => 'რეაგირება',
                            'body' => 'თქვენ მიიღეთ ახალი სამუშაო',
                            'to' => $model->performer?->expo_token,
                            'data' => [
                                'url' => 'responses',
                                'id' => $model->id,
                            ]
                        ]),
                    ];
                    (new Expo())->send($messages)->push();
                }
            }

            $model->save();

            $calendarModel = CalendarEvent::firstOrNew(['response_id' => $model->id]);

            $calendarModel->fill([
                "title" => "რეაგირება",
                "reason" => $request->job_description,
                "content" => $request->content,
                "purchaser_id" => $purchaser->id,
                "response_id" => $model->id,
                "date" => Carbon::now()
            ])->save();


            DB::commit();

            $result['success'] = true;
            $result['result'] = $model;
            $result['status'] = HttpResponse::HTTP_CREATED;
            $result['statusText'] = 'მონაცემები განახლდა წარმატებით';
        } catch (Exception $e) {
            $result['statusText'] = 'შეცდომა, მონაცემების განახლებისას';
            $result['errs'][] = 'გაურკვეველი შეცდომა! ' . $e->getMessage();
            DB::rollBack();
        }

        return response()->json($result, HttpResponse::HTTP_CREATED);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Response $response)
    {
        return view("responses.view", compact('response'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Response::firstOrNew(['id' => $id]);
        $this->authorize('view', $model);

        if (!$model['id'] && $id != 'new') {
            abort(404);
        }
        $additional = [
            'purchasers' => Purchaser::whereNot('single', 1)->get()->toArray(),
            'performers' =>  User::where('id', "!=", auth()->user()->id)->whereHas('roles', function (Builder $query) {
                $query->whereIn('name', ['ინჟინერი']);
            })->get()->toArray(),
            'systems' => System::all(),
            'regions' => Region::get()->toArray()
        ];

        $selectedSystemId = $model->system_one;
        $selectedChildId = $model->system_two;

        // Fetch and pass children only when editing an existing response
        $children = [];
        if ($selectedSystemId) {
            $selectedSystem = System::findOrFail($selectedSystemId);
            $children = $selectedSystem->children;
        }

        $setting = ['url' => ['request' => ['index' => route('responses.index', ["type" => "pending"])]]];

        return view('responses.modify', ['model' => $model, 'additional' => $additional, 'setting' => $setting, 'selectedSystemId' => $selectedSystemId, 'selectedChildId' => $selectedChildId, 'children' => $children]);
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
        $result = ['status' => HttpResponse::HTTP_FORBIDDEN, 'success' => false, 'errs' => [], 'result' => [], 'statusText' => ""];

        $response = Gate::inspect('delete', Response::find($id));

        $response = Response::find($id);
        $response->delete();
        return redirect()->back();
    }

    public function arrived($id)
    {
        $response = Response::find($id);
        $response->time = Carbon::now();
        $response->status = 10;
        $response->save();
        return back();
    }
}
